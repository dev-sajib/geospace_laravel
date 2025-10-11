<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\CompanyDetail;
use App\Models\Notification;
use App\Models\DropdownValue;
use App\Models\MenuItem;
use App\Models\VisitorLog;
use App\Helpers\AesEncryptionHelper;
use App\Helpers\MessageHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\FileUpload;

class CommonController extends Controller
{
    /**
     * User login
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'Email' => 'required|email',
                'Password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            $email = $request->input('Email');
            $password = $request->input('Password');

            $user = User::with('role')
                        ->where('email', $email)
                        ->first();

            if (!$user) {
                return response()->json(
                    MessageHelper::unauthorized('Invalid credentials'),
                    401
                );
            }

            // Check if user is active
            if (!$user->is_active) {
                return response()->json([
                    'StatusCode' => 403,
                    'Message' => 'Your account is not active. Please check your email for activation notification.',
                    'Success' => false
                ], 403);
            }

            // Check if user is verified (for freelancers)
            if ($user->role_id == 2 && $user->verification_status == 'pending') {
                return response()->json([
                    'StatusCode' => 403,
                    'Message' => 'Your account is pending verification. Please check your email for updates.',
                    'Success' => false
                ], 403);
            }

            // Check password
            if (str_starts_with($user->password_hash, '$2b$') || str_starts_with($user->password_hash, '$2y$')) {
                if (!password_verify($password, $user->password_hash)) {
                    return response()->json(
                        MessageHelper::unauthorized('Invalid credentials'),
                        401
                    );
                }
            } else {
                $decryptedPassword = AesEncryptionHelper::decrypt($user->password_hash);
                if ($password !== $decryptedPassword) {
                    return response()->json(
                        MessageHelper::unauthorized('Invalid credentials'),
                        401
                    );
                }
            }

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            // Update last login
            $user->last_login = now();
            $user->save();

            return response()->json(
                MessageHelper::success('Login successful', [
                    'Token' => $token,
                    'UserDetails' => [
                        'UserId' => $user->user_id,
                        'Email' => $user->email,
                        'RoleId' => $user->role_id,
                        'RoleName' => $user->role->role_name,
                        'IsVerified' => $user->is_verified,
                        'VerificationStatus' => $user->verification_status
                    ]
                ])
            );

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Login failed: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get menus by role ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getMenusByRoleId(Request $request): JsonResponse
    {
        try {
            $roleId = $request->query('roleId');
            \Log::info('getMenusByRoleId Request', ['$roleId'=>$roleId]);

            if (!$roleId) {
                return response()->json(
                    MessageHelper::error('RoleId is required'),
                    400
                );
            }

            // Enable query logging for this specific query
            DB::enableQueryLog();

            // Get all menus with permissions for the role
            $allMenus = DB::table('menu_items as m')
                ->join('role_menu_access as rp', 'm.menu_id', '=', 'rp.menu_id')
                ->where('rp.role_id', $roleId)
                ->where('rp.can_view', true)
                ->where('m.is_active', true)
                ->select('m.*', 'rp.can_edit', 'rp.can_delete')
                ->orderBy('m.sort_order')
                ->get();

            // Separate parent and child menus
            $parentMenus = $allMenus->whereNull('parent_menu_id');
            $childMenus = $allMenus->whereNotNull('parent_menu_id');

            // Build the hierarchical structure
            $structuredMenus = [];
            foreach ($parentMenus as $parent) {
                $parentArray = (array) $parent;

                // Get children for this parent
                $children = $childMenus->where('parent_menu_id', $parent->menu_id)->values();
                $parentArray['sub_links'] = $children->toArray();

                $structuredMenus[] = $parentArray;
            }

            return response()->json($structuredMenus);

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get notifications
     *
     * @return JsonResponse
     */
    public function notifications(): JsonResponse
    {
        try {
            $notifications = Notification::orderBy('created_at', 'desc')->get();

            if ($notifications->count() > 0) {
                return response()->json($notifications);
            } else {
                return response()->json(
                    MessageHelper::notFound('No notifications found'),
                    404
                );
            }

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Update notification as read
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateNotification(Request $request): JsonResponse
    {
        try {
            $notificationId = $request->input('NotificationId');

            if (!$notificationId) {
                return response()->json(
                    MessageHelper::error('NotificationId is required'),
                    400
                );
            }

            $notification = Notification::find($notificationId);

            if (!$notification) {
                return response()->json(
                    MessageHelper::notFound('Notification not found'),
                    404
                );
            }

            $notification->update([
                'is_read' => true,
                'read_at' => now()
            ]);

            return response()->json(
                MessageHelper::success('Notification updated successfully')
            );

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get dropdown data by category
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function dropdownDataByCategory(Request $request): JsonResponse
    {
        try {
            $category = $request->query('Category');

            if (!$category) {
                return response()->json(
                    MessageHelper::error('Category is required'),
                    400
                );
            }

            $dropdownData = DB::table('dropdown_values as dv')
                ->join('dropdown_categories as dc', 'dv.category_id', '=', 'dc.category_id')
                ->where('dc.category_name', $category)
                ->where('dv.is_active', true)
                ->where('dc.is_active', true)
                ->select('dv.*')
                ->orderBy('dv.sort_order')
                ->get();

            if ($dropdownData->count() > 0) {
                return response()->json($dropdownData);
            } else {
                return response()->json(
                    MessageHelper::notFound('No data found for category: ' . $category),
                    404
                );
            }

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    public function signUpFreelancer(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'UserPosition' => 'required|string|max:100',
                'UserName' => 'required|string|max:255',
                'Email' => 'required|email|unique:users,email',
                'PasswordHash' => 'required|string|min:6',
                'RoleId' => 'required|integer|exists:roles,role_id'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            DB::beginTransaction();

            // Encrypt password
            $encryptedPassword = AesEncryptionHelper::encrypt($request->input('PasswordHash'));

            // Create user
            $user = User::create([
                'email' => $request->input('Email'),
                'password_hash' => $encryptedPassword,
                'role_id' => $request->input('RoleId'),
                'user_position' => $request->input('UserPosition'),
                'auth_provider' => $request->input('AuthProvider'),
                'is_active' => true,
                'is_verified' => false
            ]);

            DB::commit();

            // Return with proper structure
            return response()->json(
                MessageHelper::success('User registered successfully', [
                    'UserId' => (int)$user->user_id // Ensure it's an integer
                ])
            );

        } catch (\Exception $e) {
            DB::rollBack();

            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return response()->json(
                    MessageHelper::error('Email already exists'),
                    409
                );
            }

            return response()->json(
                MessageHelper::error('Registration failed: ' . $e->getMessage()),
                500
            );
        }
    }

    public function signUpFreelancerDetails(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'UserId' => 'required|integer|exists:users,user_id',
                'FirstName' => 'required|string|max:100',
                'LastName' => 'required|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            DB::beginTransaction();

            // Create or update user details
            UserDetail::updateOrCreate(
                ['user_id' => $request->input('UserId')],
                [
                    'first_name' => $request->input('FirstName'),
                    'last_name' => $request->input('LastName'),
                    'phone' => $request->input('CellNumber'),
                    'country' => $request->input('Country'),
                    'city' => $request->input('City'),
                    'address' => $request->input('Address'),
                    'postal_code' => $request->input('PostalCode'),
                    'linkedin_url' => $request->input('LinkedInProfileLink'),
                    'profile_image' => $request->input('ProfilePhoto'),
                    'resume_or_cv' => $request->input('ResumeOrCV'),
                    'hourly_rate' => $request->input('PreferredHourlyRate') ? (float)$request->input('PreferredHourlyRate') : null
                ]
            );

            // Update user verification status to pending
            User::where('user_id', $request->input('UserId'))
                ->update(['verification_status' => 'pending']);

            DB::commit();

            return response()->json(
                MessageHelper::success('User details saved successfully')
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(
                MessageHelper::error('Failed to save user details: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Sign up company details
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function signUpCompanyDetails(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'UserId' => 'required|integer|exists:users,user_id',
                'CompanyName' => 'required|string|max:255',
                'ContactName' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            DB::beginTransaction();

            // Create or update company details
            CompanyDetail::updateOrCreate(
                ['user_id' => $request->input('UserId')],
                [
                    'company_name' => $request->input('CompanyName'),
                    'company_size' => $request->input('CompanySize'),
                    'industry' => $request->input('ProjectType'),
                    'description' => $request->input('Skills')
                ]
            );

            DB::commit();

            return response()->json(
                MessageHelper::success('Company details saved successfully')
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(
                MessageHelper::error('Failed to save company details: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Logout user (invalidate token)
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(
                MessageHelper::success('Successfully logged out')
            );
        } catch (JWTException $e) {
            return response()->json(
                MessageHelper::error('Failed to logout: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get authenticated user
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(
                    MessageHelper::unauthorized('User not found'),
                    401
                );
            }

            return response()->json($user->load(['role', 'userDetails', 'companyDetails']));
        } catch (JWTException $e) {
            return response()->json(
                MessageHelper::unauthorized('Token invalid'),
                401
            );
        }
    }

    /**
     * Log visitor activity
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logVisitor(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'RoleId' => 'required|integer',
                'UserId' => 'nullable|integer',
                'PageVisited' => 'nullable|string|max:500',
                'SessionDuration' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            // Validate user_id if provided
            $userId = $request->input('UserId');
            if ($userId) {
                $userExists = User::where('user_id', $userId)->exists();
                if (!$userExists) {
                    // Set user_id to null if user doesn't exist
                    $userId = null;
                }
            }

            // Get client IP address
            $ipAddress = $request->ip();

            // Get user agent for device info
            $deviceInfo = $request->header('User-Agent');

            // Create visitor log entry
            $visitorLog = VisitorLog::create([
                'user_id' => $userId,
                'role_id' => $request->input('RoleId'),
                'device_info' => $deviceInfo,
                'ip_address' => $ipAddress,
                'page_visited' => $request->input('PageVisited'),
                'session_duration' => $request->input('SessionDuration')
            ]);

            return response()->json(
                MessageHelper::success('Visitor activity logged successfully', [
                    'LogId' => $visitorLog->log_id
                ])
            );

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('Failed to log visitor activity: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Upload file (profile image or resume)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadFile(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120', // 5MB max
                'file_category' => 'nullable|in:Profile,Resume,Project,Invoice,Other'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            if (!$request->hasFile('file')) {
                return response()->json(
                    MessageHelper::error('No file provided'),
                    400
                );
            }

            $file = $request->file('file');
            $userId = auth()->user() ? auth()->id() : null;

            // Determine file category based on mime type
            $mimeType = $file->getMimeType();
            $fileCategory = $request->input('file_category');

            if (!$fileCategory) {
                if (str_starts_with($mimeType, 'image/')) {
                    $fileCategory = 'Profile';
                } elseif ($mimeType === 'application/pdf') {
                    $fileCategory = 'Resume';
                } else {
                    $fileCategory = 'Other';
                }
            }

            // Generate unique filename
            $originalFilename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $storedFilename = time() . '_' . uniqid() . '.' . $extension;

            // Determine storage path based on category
            $uploadPath = $fileCategory === 'Profile' ? 'uploads/profiles' : 'uploads/resumes';

            // Store file
            $filePath = $file->storeAs($uploadPath, $storedFilename, 'public');

            // Save to database
            if ($userId) {
                FileUpload::create([
                    'user_id' => $userId,
                    'original_filename' => $originalFilename,
                    'stored_filename' => $storedFilename,
                    'file_path' => $filePath,
                    'file_size' => $file->getSize(),
                    'mime_type' => $mimeType,
                    'file_category' => $fileCategory
                ]);
            }

            return response()->json(
                MessageHelper::success('File uploaded successfully', [
                    'filePath' => 'storage/' . $filePath,
                    'originalName' => $originalFilename,
                    'storedName' => $storedFilename,
                    'fileSize' => $file->getSize(),
                    'mimeType' => $mimeType
                ])
            );

        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());

            return response()->json(
                MessageHelper::error('File upload failed: ' . $e->getMessage()),
                500
            );
        }
    }
}
