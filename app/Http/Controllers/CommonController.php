<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\CompanyDetail;
use App\Models\Notification;
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
use Illuminate\Support\Facades\Mail;
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

            // Check verification status for freelancers and companies
            if (in_array($user->role_id, [2, 3])) { // 2 = Freelancer, 3 = Company
                if ($user->verification_status === 'pending') {
                    return response()->json([
                        'StatusCode' => 403,
                        'Message' => 'Your account is pending verification. Please check your email for updates.',
                        'Success' => false
                    ], 403);
                }

                if ($user->verification_status === 'rejected') {
                    return response()->json([
                        'StatusCode' => 403,
                        'Message' => 'Your account verification was rejected. Please contact support.',
                        'Success' => false
                    ], 403);
                }
            }

            // Check password - support both bcrypt and AES encryption
            if (str_starts_with($user->password_hash, '$2b$') || str_starts_with($user->password_hash, '$2y$')) {
                if (!Hash::check($password, $user->password_hash)) {
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
                        'VerificationStatus' => $user->verification_status,
                        'UserPosition' => $user->user_position
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
     * Freelancer Signup - Step 1 (Email validation only)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function signUpFreelancer(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'UserPosition' => 'required|string|max:100',
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

            // Validate role is Freelancer (role_id = 2)
            if ($request->input('RoleId') != 2) {
                return response()->json(
                    MessageHelper::error('Invalid role for freelancer signup'),
                    400
                );
            }

            // Just validate and return data for next step
            return response()->json(
                MessageHelper::success('Email validated successfully. Please complete your profile.', [
                    'Email' => $request->input('Email'),
                    'UserPosition' => $request->input('UserPosition'),
                    'RoleId' => $request->input('RoleId'),
                    'PasswordHash' => $request->input('PasswordHash'),
                    'AuthProvider' => $request->input('AuthProvider', 'Manual')
                ])
            );

        } catch (\Exception $e) {
            Log::error('Freelancer signup validation error: ' . $e->getMessage());

            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'unique')) {
                return response()->json(
                    MessageHelper::error('Email already exists'),
                    409
                );
            }

            return response()->json(
                MessageHelper::error('Validation failed: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Freelancer Signup - Step 2 (Complete Profile)
     * Creates user account with all details
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function signUpFreelancerDetails(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                // User basic info from step 1
                'Email' => 'required|email|unique:users,email',
                'PasswordHash' => 'required|string|min:6',
                'UserPosition' => 'required|string|max:100',
                'RoleId' => 'required|integer|exists:roles,role_id',
                'AuthProvider' => 'nullable|string|max:50',

                // User details from step 2 (matching user_details schema)
                'FirstName' => 'required|string|max:100',
                'LastName' => 'required|string|max:100',
                'Phone' => 'nullable|string|max:20',
                'Address' => 'nullable|string',
                'City' => 'nullable|string|max:100',
                'State' => 'nullable|string|max:100',
                'PostalCode' => 'nullable|string|max:20',
                'Country' => 'nullable|string|max:100',
                'ProfileImage' => 'nullable|string|max:500',
                'Bio' => 'nullable|string',
                'LinkedInUrl' => 'nullable|string|max:500',
                'WebsiteUrl' => 'nullable|string|max:500',
                'ResumeOrCV' => 'nullable|string|max:500',
                'HourlyRate' => 'nullable|numeric|min:0',
                'AvailabilityStatus' => 'nullable|in:Available,Busy,Unavailable'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            DB::beginTransaction();

            try {
                // Encrypt password using AES or use bcrypt
                $encryptedPassword = AesEncryptionHelper::encrypt($request->input('PasswordHash'));

                // Create user
                $user = User::create([
                    'email' => $request->input('Email'),
                    'password_hash' => $encryptedPassword,
                    'role_id' => $request->input('RoleId'),
                    'user_position' => $request->input('UserPosition'),
                    'auth_provider' => $request->input('AuthProvider', 'Manual'),
                    'is_active' => true,
                    'is_verified' => false,
                    'verification_status' => 'pending', // Valid enum value from schema
                    'email_verified_at' => null
                ]);

                // Create user details matching the schema
                UserDetail::create([
                    'user_id' => $user->user_id,
                    'first_name' => $request->input('FirstName'),
                    'last_name' => $request->input('LastName'),
                    'phone' => $request->input('Phone'),
                    'address' => $request->input('Address'),
                    'city' => $request->input('City'),
                    'state' => $request->input('State'),
                    'postal_code' => $request->input('PostalCode'),
                    'country' => $request->input('Country'),
                    'profile_image' => $request->input('ProfileImage'),
                    'bio' => $request->input('Bio'),
                    'linkedin_url' => $request->input('LinkedInUrl'),
                    'website_url' => $request->input('WebsiteUrl'),
                    'resume_or_cv' => $request->input('ResumeOrCV'),
                    'hourly_rate' => $request->input('HourlyRate') ? (float)$request->input('HourlyRate') : null,
                    'availability_status' => $request->input('AvailabilityStatus', 'Available')
                ]);

                DB::commit();

                // Send welcome email
                try {
                    Mail::send('emails.verification_pending', ['user' => $user], function ($message) use ($user) {
                        $message->to($user->email)
                                ->subject('Welcome to GeoSpace - Verification Pending');
                    });
                } catch (\Exception $mailException) {
                    Log::error('Failed to send welcome email: ' . $mailException->getMessage());
                }

                return response()->json(
                    MessageHelper::success('Registration successful! One of our agents will contact you soon.', [
                        'UserId' => (int)$user->user_id,
                        'Email' => $user->email,
                        'VerificationStatus' => $user->verification_status
                    ])
                );

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Freelancer registration error: ' . $e->getMessage());

            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'unique')) {
                return response()->json(
                    MessageHelper::error('Email already exists'),
                    409
                );
            }

            return response()->json(
                MessageHelper::error('Failed to complete registration: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Company Signup
     * Fixed to match company_details schema
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function signUpCompanyDetails(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                // User credentials
                'Email' => 'required|email|unique:users,email',
                'PasswordHash' => 'required|string|min:6',
                'RoleId' => 'required|integer|exists:roles,role_id',
                'UserPosition' => 'nullable|string|max:100',
                'AuthProvider' => 'nullable|string|max:50',

                // Company details matching schema
                'CompanyName' => 'required|string|max:255',
                'CompanyType' => 'nullable|string|max:100',
                'Industry' => 'nullable|string|max:100',
                'CompanySize' => 'nullable|in:1-10,11-50,51-200,201-500,500+', // Enum from schema
                'Website' => 'nullable|string|max:500',
                'Description' => 'nullable|string',
                'FoundedYear' => 'nullable|integer|min:1800|max:' . date('Y'),
                'Headquarters' => 'nullable|string|max:255',
                'Logo' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            // Validate role is Company (role_id = 3)
            if ($request->input('RoleId') != 3) {
                return response()->json(
                    MessageHelper::error('Invalid role for company signup'),
                    400
                );
            }

            DB::beginTransaction();

            try {
                // Encrypt password
                $encryptedPassword = AesEncryptionHelper::encrypt($request->input('PasswordHash'));

                // Create user
                $user = User::create([
                    'email' => $request->input('Email'),
                    'password_hash' => $encryptedPassword,
                    'role_id' => $request->input('RoleId'),
                    'user_position' => $request->input('UserPosition', 'Company Representative'),
                    'auth_provider' => $request->input('AuthProvider', 'Manual'),
                    'is_active' => true,
                    'is_verified' => false,
                    'verification_status' => 'pending'
                ]);

                // Create company details - matching schema exactly
                CompanyDetail::create([
                    'user_id' => $user->user_id,
                    'company_name' => $request->input('CompanyName'),
                    'company_type' => $request->input('CompanyType'),
                    'industry' => $request->input('Industry'),
                    'company_size' => $request->input('CompanySize'),
                    'website' => $request->input('Website'),
                    'description' => $request->input('Description'),
                    'founded_year' => $request->input('FoundedYear'),
                    'headquarters' => $request->input('Headquarters'),
                    'logo' => $request->input('Logo')
                ]);

                DB::commit();

                // Send welcome email
                try {
                    Mail::send('emails.verification_pending', ['user' => $user, 'company' => $request->input('CompanyName')], function ($message) use ($user) {
                        $message->to($user->email)
                                ->subject('Welcome to GeoSpace - Company Verification Pending');
                    });
                } catch (\Exception $mailException) {
                    Log::error('Failed to send welcome email: ' . $mailException->getMessage());
                }

                return response()->json(
                    MessageHelper::success('Company registered successfully! One of our agents will contact you soon.', [
                        'UserId' => (int)$user->user_id,
                        'Email' => $user->email,
                        'CompanyName' => $request->input('CompanyName'),
                        'VerificationStatus' => $user->verification_status
                    ])
                );

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Company registration error: ' . $e->getMessage());

            if (str_contains($e->getMessage(), 'Duplicate entry') || str_contains($e->getMessage(), 'unique')) {
                return response()->json(
                    MessageHelper::error('Email already exists'),
                    409
                );
            }

            return response()->json(
                MessageHelper::error('Failed to complete registration: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * File Upload
     * Improved with proper file tracking
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadFile(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:10240', // 10MB max
                'FileCategory' => 'nullable|in:Profile,Resume,Project,Invoice,Other',
                'EntityType' => 'nullable|string|max:100',
                'EntityId' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            if (!$request->hasFile('file')) {
                return response()->json(
                    MessageHelper::error('No file uploaded'),
                    400
                );
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            // Generate unique filename
            $storedFileName = time() . '_' . uniqid() . '.' . $extension;

            // Store in public/uploads
            $path = $file->storeAs('uploads', $storedFileName, 'public');
            $fullPath = 'storage/' . $path;

            // Track file in database if user is authenticated
            $user = auth()->user();
            if ($user) {
                FileUpload::create([
                    'user_id' => $user->user_id,
                    'original_filename' => $originalName,
                    'stored_filename' => $storedFileName,
                    'file_path' => $fullPath,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                    'file_category' => $request->input('FileCategory', 'Other'),
                    'entity_type' => $request->input('EntityType'),
                    'entity_id' => $request->input('EntityId')
                ]);
            }

            return response()->json(
                MessageHelper::success('File uploaded successfully', [
                    'FilePath' => $fullPath,
                    'OriginalFileName' => $originalName,
                    'StoredFileName' => $storedFileName,
                    'FileSize' => $fileSize,
                    'MimeType' => $mimeType
                ])
            );

        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('File upload failed: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get authenticated user details
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(
                    MessageHelper::unauthorized('User not authenticated'),
                    401
                );
            }

            // Load relationships
            $user->load(['role', 'userDetails', 'companyDetails']);

            $response = [
                'UserId' => $user->user_id,
                'Email' => $user->email,
                'RoleId' => $user->role_id,
                'RoleName' => $user->role->role_name ?? null,
                'UserPosition' => $user->user_position,
                'IsVerified' => $user->is_verified,
                'IsActive' => $user->is_active,
                'VerificationStatus' => $user->verification_status,
                'LastLogin' => $user->last_login
            ];

            // Add user details if exists (for freelancers)
            if ($user->userDetails) {
                $response['UserDetails'] = [
                    'FirstName' => $user->userDetails->first_name,
                    'LastName' => $user->userDetails->last_name,
                    'Phone' => $user->userDetails->phone,
                    'Address' => $user->userDetails->address,
                    'City' => $user->userDetails->city,
                    'State' => $user->userDetails->state,
                    'PostalCode' => $user->userDetails->postal_code,
                    'Country' => $user->userDetails->country,
                    'ProfileImage' => $user->userDetails->profile_image,
                    'Bio' => $user->userDetails->bio,
                    'LinkedInUrl' => $user->userDetails->linkedin_url,
                    'WebsiteUrl' => $user->userDetails->website_url,
                    'ResumeOrCV' => $user->userDetails->resume_or_cv,
                    'HourlyRate' => $user->userDetails->hourly_rate,
                    'AvailabilityStatus' => $user->userDetails->availability_status
                ];
            }

            // Add company details if exists (for companies)
            if ($user->companyDetails) {
                $response['CompanyDetails'] = [
                    'CompanyId' => $user->companyDetails->company_id,
                    'CompanyName' => $user->companyDetails->company_name,
                    'CompanyType' => $user->companyDetails->company_type,
                    'Industry' => $user->companyDetails->industry,
                    'CompanySize' => $user->companyDetails->company_size,
                    'Website' => $user->companyDetails->website,
                    'Description' => $user->companyDetails->description,
                    'FoundedYear' => $user->companyDetails->founded_year,
                    'Headquarters' => $user->companyDetails->headquarters,
                    'Logo' => $user->companyDetails->logo
                ];
            }

            return response()->json(
                MessageHelper::success('User details retrieved successfully', $response)
            );

        } catch (\Exception $e) {
            Log::error('Get user error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to retrieve user details'),
                500
            );
        }
    }

    /**
     * Logout
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(
                MessageHelper::success('Logged out successfully')
            );

        } catch (JWTException $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Logout failed'),
                500
            );
        }
    }

    /**
     * Get notifications for authenticated user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function notifications(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(
                    MessageHelper::unauthorized('User not authenticated'),
                    401
                );
            }

            $limit = $request->query('limit', 50);
            $isRead = $request->query('is_read'); // null = all, true = read, false = unread

            $query = Notification::where('user_id', $user->user_id);

            if ($isRead !== null) {
                $query->where('is_read', filter_var($isRead, FILTER_VALIDATE_BOOLEAN));
            }

            $notifications = $query->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($notification) {
                    return [
                        'NotificationId' => $notification->notification_id,
                        'Title' => $notification->title,
                        'Message' => $notification->message,
                        'Type' => $notification->type,
                        'ActionUrl' => $notification->action_url,
                        'IsRead' => (bool)$notification->is_read,
                        'ReadAt' => $notification->read_at,
                        'CreatedAt' => $notification->created_at
                    ];
                });

            return response()->json(
                MessageHelper::success('Notifications retrieved successfully', $notifications)
            );

        } catch (\Exception $e) {
            Log::error('Get notifications error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to retrieve notifications'),
                500
            );
        }
    }

    /**
     * Mark notification as read
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateNotification(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'NotificationId' => 'required|integer|exists:notifications,notification_id'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            $user = auth()->user();
            $notification = Notification::where('notification_id', $request->input('NotificationId'))
                                       ->where('user_id', $user->user_id)
                                       ->first();

            if (!$notification) {
                return response()->json(
                    MessageHelper::notFound('Notification not found'),
                    404
                );
            }

            $notification->is_read = true;
            $notification->read_at = now();
            $notification->save();

            return response()->json(
                MessageHelper::success('Notification marked as read')
            );

        } catch (\Exception $e) {
            Log::error('Update notification error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to update notification'),
                500
            );
        }
    }

    /**
     * Mark all notifications as read
     *
     * @return JsonResponse
     */
    public function markAllNotificationsRead(): JsonResponse
    {
        try {
            $user = auth()->user();

            Notification::where('user_id', $user->user_id)
                       ->where('is_read', false)
                       ->update([
                           'is_read' => true,
                           'read_at' => now()
                       ]);

            return response()->json(
                MessageHelper::success('All notifications marked as read')
            );

        } catch (\Exception $e) {
            Log::error('Mark all notifications error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to update notifications'),
                500
            );
        }
    }

    /**
     * Log visitor activity
     * Fixed to match visitor_logs schema
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logVisitor(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'RoleId' => 'required|integer|exists:roles,role_id',
                'DeviceInfo' => 'nullable|string',
                'PageVisited' => 'nullable|string|max:500',
                'SessionDuration' => 'nullable|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            $user = auth()->user();

            VisitorLog::create([
                'user_id' => $user ? $user->user_id : null,
                'role_id' => $request->input('RoleId'),
                'device_info' => $request->input('DeviceInfo', $request->header('User-Agent')),
                'ip_address' => $request->ip(),
                'page_visited' => $request->input('PageVisited'),
                'session_duration' => $request->input('SessionDuration')
            ]);

            return response()->json(
                MessageHelper::success('Visitor logged successfully')
            );

        } catch (\Exception $e) {
            Log::error('Log visitor error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to log visitor'),
                500
            );
        }
    }

    /**
     * Get user statistics
     * NEW: Useful for dashboard
     *
     * @return JsonResponse
     */
    public function getUserStats(): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(
                    MessageHelper::unauthorized('User not authenticated'),
                    401
                );
            }

            $stats = [];

            // Get role-specific stats
            switch ($user->role_id) {
                case 2: // Freelancer
                    $stats = [
                        'TotalContracts' => DB::table('contracts')->where('freelancer_id', $user->user_id)->count(),
                        'ActiveContracts' => DB::table('contracts')->where('freelancer_id', $user->user_id)->where('status', 'Active')->count(),
                        'TotalEarnings' => DB::table('freelancer_earnings')->where('freelancer_id', $user->user_id)->value('total_earned') ?? 0,
                        'PendingPayments' => DB::table('freelancer_earnings')->where('freelancer_id', $user->user_id)->value('pending_amount') ?? 0,
                        'PendingTimesheets' => DB::table('timesheets')->where('freelancer_id', $user->user_id)->where('status_id', 1)->count(),
                        'UnreadNotifications' => DB::table('notifications')->where('user_id', $user->user_id)->where('is_read', false)->count()
                    ];
                    break;

                case 3: // Company
                    $companyId = DB::table('company_details')->where('user_id', $user->user_id)->value('company_id');
                    $stats = [
                        'TotalProjects' => DB::table('projects')->where('company_id', $companyId)->count(),
                        'ActiveProjects' => DB::table('projects')->where('company_id', $companyId)->where('status', 'In Progress')->count(),
                        'TotalContracts' => DB::table('contracts')->where('company_id', $companyId)->count(),
                        'ActiveFreelancers' => DB::table('contracts')->where('company_id', $companyId)->where('status', 'Active')->distinct('freelancer_id')->count(),
                        'PendingTimesheets' => DB::table('timesheets')->where('company_id', $companyId)->where('status_id', 1)->count(),
                        'UnreadNotifications' => DB::table('notifications')->where('user_id', $user->user_id)->where('is_read', false)->count()
                    ];
                    break;

                case 1: // Admin
                    $stats = [
                        'TotalUsers' => DB::table('users')->where('is_active', true)->count(),
                        'PendingVerifications' => DB::table('users')->where('verification_status', 'pending')->count(),
                        'ActiveContracts' => DB::table('contracts')->where('status', 'Active')->count(),
                        'OpenDisputes' => DB::table('dispute_tickets')->where('status_id', 1)->count(),
                        'PendingPayments' => DB::table('payment_requests')->where('status', 'Pending')->count()
                    ];
                    break;

                case 4: // Support
                    $stats = [
                        'OpenDisputes' => DB::table('dispute_tickets')->where('status_id', 1)->count(),
                        'AssignedDisputes' => DB::table('dispute_tickets')->where('assigned_to', $user->user_id)->whereIn('status_id', [1, 2])->count(),
                        'ActiveChatSessions' => DB::table('chat_sessions')->where('support_agent_id', $user->user_id)->where('status', 'Active')->count()
                    ];
                    break;
            }

            return response()->json(
                MessageHelper::success('Statistics retrieved successfully', $stats)
            );

        } catch (\Exception $e) {
            Log::error('Get user stats error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to retrieve statistics'),
                500
            );
        }
    }
}
