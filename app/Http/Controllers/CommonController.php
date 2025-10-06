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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

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

            // Find user by email first
            $user = User::with('role')
                ->where('email', $email)
                ->where('is_active', true)
                ->first();

            if (!$user) {
                return response()->json(
                    MessageHelper::unauthorized('Invalid credentials'),
                    401
                );
            }

            // Check if password is bcrypt hash or AES encrypted
            if (str_starts_with($user->password_hash, '$2b$') || str_starts_with($user->password_hash, '$2y$')) {
                // Bcrypt password - use PHP's password_verify for better compatibility
                if (!password_verify($password, $user->password_hash)) {
                    return response()->json(
                        MessageHelper::unauthorized('Invalid credentials'),
                        401
                    );
                }
            } else {
                // AES encrypted password - decrypt and compare
                try {
                    $decryptedPassword = AesEncryptionHelper::decrypt($user->password_hash);
                    if ($decryptedPassword !== $password) {
                        return response()->json(
                            MessageHelper::unauthorized('Invalid credentials'),
                            401
                        );
                    }
                } catch (\Exception $e) {
                    return response()->json(
                        MessageHelper::unauthorized('Invalid credentials'),
                        401
                    );
                }
            }

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            $userDetails = [
                'UserId' => $user->user_id,
                'UserName' => $user->email, // Using email as username
                'Email' => $user->email,
                'RoleId' => $user->role_id,
                'RoleName' => $user->role->role_name ?? 'Unknown'
            ];

            $response = [
                'Token' => $token,
                'UserDetails' => $userDetails
            ];

            // Update last login
            $user->update(['last_login' => now()]);

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json(
                MessageHelper::error('An internal server error occurred: ' . $e->getMessage()),
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
                ->join('role_permissions as rp', 'm.menu_id', '=', 'rp.menu_id')
                ->where('rp.role_id', $roleId)
                ->where('rp.can_view', true)
                ->where('m.is_active', true)
                ->select('m.*', 'rp.can_create', 'rp.can_edit', 'rp.can_delete')
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

    /**
     * Sign up freelancer
     *
     * @param Request $request
     * @return JsonResponse
     */
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

            return response()->json(
                MessageHelper::success('User registered successfully', ['UserId' => $user->user_id])
            );

        } catch (\Exception $e) {
            DB::rollBack();

            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return response()->json(
                    MessageHelper::error('Email already exists', 409),
                    409
                );
            }

            return response()->json(
                MessageHelper::error('Registration failed: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Sign up freelancer details
     *
     * @param Request $request
     * @return JsonResponse
     */
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
}
