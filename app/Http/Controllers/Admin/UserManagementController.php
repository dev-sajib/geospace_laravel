<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\CompanyDetail;
use App\Helpers\MessageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    /**
     * Get verified user list
     *
     * @return JsonResponse
     */
    public function verifiedUserList(): JsonResponse
    {
        try {
            $users = DB::table('users as u')
                ->join('roles as r', 'u.role_id', '=', 'r.role_id')
                ->leftJoin('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->leftJoin('company_details as cd', 'u.user_id', '=', 'cd.user_id')
                ->select(
                    'u.user_id',
                    'u.email as Email',
                    'u.user_position',
                    'u.is_active',
                    'u.is_verified',
                    'u.last_login as LastActiveDate',
                    'u.created_at as JoinedDate',
                    'u.email_verified_at as VerifiedDate',
                    'r.role_name as Role',
                    'ud.first_name',
                    'ud.last_name',
                    'ud.phone',
                    'ud.country',
                    'ud.city',
                    'cd.company_name'
                )
                ->where('u.is_verified', true)
                ->where('u.is_active', true)
                ->orderBy('u.created_at', 'desc')
                ->get();

            // Transform the data to match frontend expectations
            $transformedUsers = $users->map(function ($user) {
                return [
                    'UserName' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->Email,
                    'Type' => $user->Role ?? 'Unknown',
                    'Email' => $user->Email,
                    'Status' => $user->is_active ? 'Active' : 'Inactive',
                    'JoinedDate' => $user->JoinedDate,
                    'LastActiveDate' => $user->LastActiveDate,
                    'VerifiedDate' => $user->VerifiedDate,
                    'Role' => $user->Role,
                    'user_id' => $user->user_id,
                    'phone' => $user->phone,
                    'country' => $user->country,
                    'city' => $user->city,
                    'company_name' => $user->company_name
                ];
            });

            if ($transformedUsers->count() > 0) {
                return response()->json($transformedUsers);
            } else {
                return response()->json(
                    MessageHelper::notFound('No verified users found'),
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
     * Get pending verification list
     *
     * @return JsonResponse
     */
    public function pendingVerificationList(): JsonResponse
    {
        try {
            $users = DB::table('users as u')
                ->join('roles as r', 'u.role_id', '=', 'r.role_id')
                ->leftJoin('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->leftJoin('company_details as cd', 'u.user_id', '=', 'cd.user_id')
                ->select(
                    'u.user_id',
                    'u.email as Email',
                    'u.user_position',
                    'u.auth_provider',
                    'u.created_at as JoinedDate',
                    'u.is_active',
                    'r.role_name as Role',
                    'ud.first_name',
                    'ud.last_name',
                    'ud.phone',
                    'ud.country',
                    'ud.city',
                    'ud.resume_or_cv',
                    'cd.company_name',
                    'cd.company_type',
                    'cd.industry'
                )
                ->where('u.is_verified', false)
                ->where('u.is_active', true)
                ->orderBy('u.created_at', 'desc')
                ->get();

            // Transform the data to match frontend expectations
            $transformedUsers = $users->map(function ($user) {
                return [
                    'UserName' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->Email,
                    'Type' => $user->Role ?? 'Unknown',
                    'Email' => $user->Email,
                    'Status' => 'Pending',
                    'JoinedDate' => $user->JoinedDate,
                    'Role' => $user->Role,
                    'user_id' => $user->user_id,
                    'phone' => $user->phone,
                    'country' => $user->country,
                    'city' => $user->city,
                    'company_name' => $user->company_name,
                    'company_type' => $user->company_type,
                    'industry' => $user->industry,
                    'resume_or_cv' => $user->resume_or_cv,
                    'auth_provider' => $user->auth_provider
                ];
            });

            if ($transformedUsers->count() > 0) {
                return response()->json($transformedUsers);
            } else {
                return response()->json(
                    MessageHelper::notFound('No pending verification users found'),
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
     * Get suspended accounts list
     *
     * @return JsonResponse
     */
    public function suspendedAccountsList(): JsonResponse
    {
        try {
            $users = DB::table('users as u')
                ->join('roles as r', 'u.role_id', '=', 'r.role_id')
                ->leftJoin('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->leftJoin('company_details as cd', 'u.user_id', '=', 'cd.user_id')
                ->select(
                    'u.user_id',
                    'u.email as Email',
                    'u.user_position',
                    'u.last_login as LastActiveDate',
                    'u.created_at as JoinedDate',
                    'u.updated_at',
                    'u.is_active',
                    'r.role_name as Role',
                    'ud.first_name',
                    'ud.last_name',
                    'ud.phone',
                    'ud.country',
                    'cd.company_name'
                )
                ->where('u.is_active', false)
                ->orderBy('u.updated_at', 'desc')
                ->get();

            // Transform the data to match frontend expectations
            $transformedUsers = $users->map(function ($user) {
                return [
                    'UserName' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->Email,
                    'Type' => $user->Role ?? 'Unknown',
                    'Email' => $user->Email,
                    'Status' => 'Suspended',
                    'JoinedDate' => $user->JoinedDate,
                    'LastActiveDate' => $user->LastActiveDate,
                    'Role' => $user->Role,
                    'user_id' => $user->user_id,
                    'phone' => $user->phone,
                    'country' => $user->country,
                    'company_name' => $user->company_name,
                    'updated_at' => $user->updated_at
                ];
            });

            if ($transformedUsers->count() > 0) {
                return response()->json($transformedUsers);
            } else {
                return response()->json(
                    MessageHelper::notFound('No suspended accounts found'),
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
     * Update user status (activate/deactivate/verify)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUserStatus(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'UserId' => 'required|integer|exists:users,user_id',
                'IsActive' => 'required|boolean',
                'UpdatedBy' => 'required|integer|exists:users,user_id'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            $user = User::find($request->input('UserId'));

            if (!$user) {
                return response()->json(
                    MessageHelper::notFound('User not found'),
                    404
                );
            }

            DB::beginTransaction();

            $user->update([
                'is_active' => $request->input('IsActive'),
                'updated_at' => now()
            ]);

            // Log the activity
            DB::table('activity_logs')->insert([
                'user_id' => $request->input('UpdatedBy'),
                'action' => $request->input('IsActive') ? 'User Activated' : 'User Deactivated',
                'entity_type' => 'User',
                'entity_id' => $user->user_id,
                'old_values' => json_encode(['is_active' => !$request->input('IsActive')]),
                'new_values' => json_encode(['is_active' => $request->input('IsActive')]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            DB::commit();

            $message = $request->input('IsActive') ? 'User activated successfully' : 'User deactivated successfully';

            return response()->json(
                MessageHelper::success($message)
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(
                MessageHelper::error('Failed to update user status: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Verify user account
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyUser(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'UserId' => 'required|integer|exists:users,user_id',
                'UpdatedBy' => 'required|integer|exists:users,user_id'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            $user = User::find($request->input('UserId'));

            if (!$user) {
                return response()->json(
                    MessageHelper::notFound('User not found'),
                    404
                );
            }

            DB::beginTransaction();

            $user->update([
                'is_verified' => true,
                'email_verified_at' => now(),
                'updated_at' => now()
            ]);

            // Log the activity
            DB::table('activity_logs')->insert([
                'user_id' => $request->input('UpdatedBy'),
                'action' => 'User Verified',
                'entity_type' => 'User',
                'entity_id' => $user->user_id,
                'old_values' => json_encode(['is_verified' => false]),
                'new_values' => json_encode(['is_verified' => true]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            // Create notification for user
            DB::table('notifications')->insert([
                'user_id' => $user->user_id,
                'title' => 'Account Verified',
                'message' => 'Your account has been verified and is now active.',
                'type' => 'Success',
                'is_read' => false,
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json(
                MessageHelper::success('User verified successfully')
            );

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(
                MessageHelper::error('Failed to verify user: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Get user details by ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserDetails(Request $request): JsonResponse
    {
        try {
            $userId = $request->query('UserId');

            if (!$userId) {
                return response()->json(
                    MessageHelper::error('UserId is required'),
                    400
                );
            }

            $userDetails = DB::table('users as u')
                ->join('roles as r', 'u.role_id', '=', 'r.role_id')
                ->leftJoin('user_details as ud', 'u.user_id', '=', 'ud.user_id')
                ->leftJoin('company_details as cd', 'u.user_id', '=', 'cd.user_id')
                ->select(
                    'u.*',
                    'r.role_name',
                    'ud.*',
                    'cd.company_name',
                    'cd.company_type',
                    'cd.industry',
                    'cd.company_size',
                    'cd.website as company_website',
                    'cd.description as company_description'
                )
                ->where('u.user_id', $userId)
                ->first();

            if ($userDetails) {
                return response()->json($userDetails);
            } else {
                return response()->json(
                    MessageHelper::notFound('User not found'),
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
}
