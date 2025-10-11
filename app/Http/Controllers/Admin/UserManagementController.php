<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use App\Helpers\MessageHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    /**
     * Get pending verification users
     */
    public function pendingVerificationList(Request $request): JsonResponse
    {
        try {
            $users = User::with(['userDetails', 'role'])
                         ->where('role_id', 2) // Freelancers only
                         ->whereIn('verification_status', ['pending', 'awaiting'])
                         ->orderBy('created_at', 'desc')
                         ->get()
                         ->map(function ($user) {
                             return [
                                 'UserId' => $user->user_id,
                                 'Name' => $user->userDetails
                                     ? $user->userDetails->first_name . ' ' . $user->userDetails->last_name
                                     : 'N/A',
                                 'Email' => $user->email,
                                 'Type' => $user->role->role_name ?? 'Freelancer',
                                 'Status' => ucfirst($user->verification_status),
                                 'CreatedAt' => $user->created_at->format('Y-m-d H:i:s'),
                                 'ActionNeeded' => $user->verification_status === 'pending'
                                     ? 'Review Documents'
                                     : 'Validate Account'
                             ];
                         });

            return response()->json(
                MessageHelper::success('Pending verifications retrieved successfully', $users)
            );

        } catch (\Exception $e) {
            Log::error('Get pending verifications error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to retrieve pending verifications'),
                500
            );
        }
    }

    /**
     * Get user details for review
     */
    public function getUserDetails(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'UserId' => 'required|integer|exists:users,user_id'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            $userId = $request->input('UserId');

            $user = User::with(['userDetails', 'role'])
                        ->where('user_id', $userId)
                        ->first();

            if (!$user) {
                return response()->json(
                    MessageHelper::notFound('User not found'),
                    404
                );
            }

            $details = $user->userDetails;

            $userData = [
                'UserId' => $user->user_id,
                'Email' => $user->email,
                'UserPosition' => $user->user_position,
                'RoleName' => $user->role->role_name ?? 'N/A',
                'VerificationStatus' => $user->verification_status,
                'IsActive' => $user->is_active,
                'CreatedAt' => $user->created_at->format('Y-m-d H:i:s'),

                // User details
                'FirstName' => $details->first_name ?? 'N/A',
                'LastName' => $details->last_name ?? 'N/A',
                'Phone' => $details->phone ?? 'N/A',
                'Country' => $details->country ?? 'N/A',
                'City' => $details->city ?? 'N/A',
                'Address' => $details->address ?? 'N/A',
                'PostalCode' => $details->postal_code ?? 'N/A',
                'ProfileImage' => $details->profile_image
                    ? env('APP_URL') . '/' . $details->profile_image
                    : null,
                'ResumeCV' => $details->resume_or_cv
                    ? env('APP_URL') . '/' . $details->resume_or_cv
                    : null,
                'HourlyRate' => $details->hourly_rate ?? 0,
                'LinkedInUrl' => $details->linkedin_url ?? 'N/A',
                'Bio' => $details->bio ?? 'N/A'
            ];

            return response()->json(
                MessageHelper::success('User details retrieved successfully', $userData)
            );

        } catch (\Exception $e) {
            Log::error('Get user details error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to retrieve user details'),
                500
            );
        }
    }

    /**
     * Send verification request email (Phase 1 to Phase 2)
     */
    public function sendVerificationRequest(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'UserId' => 'required|integer|exists:users,user_id'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            $userId = $request->input('UserId');
            $user = User::with('userDetails')->find($userId);

            if (!$user) {
                return response()->json(
                    MessageHelper::notFound('User not found'),
                    404
                );
            }

            DB::beginTransaction();

            // Update status to awaiting
            $user->verification_status = 'awaiting';
            $user->save();

            // Send email
            $userName = $user->userDetails
                ? $user->userDetails->first_name . ' ' . $user->userDetails->last_name
                : 'User';

            Mail::send([], [], function ($message) use ($user, $userName) {
                $message->to($user->email)
                        ->subject('GeoSpace - Verification Phase 2')
                        ->html("
                        <h2>Hello {$userName},</h2>
                        <p>Congratulations! You are eligible for the first phase.</p>
                        <p>Are you ready for the verification phase?</p>
                        <p>If yes, please reply with 'YES' along with your available free time and date.</p>
                        <p>One of our interviewers will contact you shortly.</p>
                        <br>
                        <p>Best regards,</p>
                        <p>GeoSpace Team</p>
                    ");
            });

            DB::commit();

            return response()->json(
                MessageHelper::success('Verification request sent successfully')
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Send verification request error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to send verification request'),
                500
            );
        }
    }

    /**
     * Final verification - Approve or Reject
     */
    public function finalVerification(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'UserId' => 'required|integer|exists:users,user_id',
                'Action' => 'required|in:approve,reject'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::validationError($validator->errors()->toArray()),
                    422
                );
            }

            $userId = $request->input('UserId');
            $action = $request->input('Action');

            $user = User::with('userDetails')->find($userId);

            if (!$user) {
                return response()->json(
                    MessageHelper::notFound('User not found'),
                    404
                );
            }

            DB::beginTransaction();

            $userName = $user->userDetails
                ? $user->userDetails->first_name . ' ' . $user->userDetails->last_name
                : 'User';

            if ($action === 'approve') {
                // Approve user
                $user->verification_status = 'verified';
                $user->is_verified = true;
                $user->is_active = true;
                $user->save();

                // Send welcome email
                Mail::send([], [], function ($message) use ($user, $userName) {
                    $message->to($user->email)
                            ->subject('Welcome to GeoSpace!')
                            ->html("
                            <h2>Welcome to GeoSpace, {$userName}!</h2>
                            <p>Your account has been successfully verified and activated.</p>
                            <p>You can now log in and start exploring opportunities on our platform.</p>
                            <p>Login here: <a href='" . env('FRONTEND_URL') . "/login'>Login to GeoSpace</a></p>
                            <br>
                            <p>Best regards,</p>
                            <p>GeoSpace Team</p>
                        ");
                });

                DB::commit();

                return response()->json(
                    MessageHelper::success('User approved and welcome email sent')
                );

            } else {
                // Reject user
                $user->verification_status = 'rejected';
                $user->is_active = false;
                $user->save();

                // Send rejection email
                Mail::send([], [], function ($message) use ($user, $userName) {
                    $message->to($user->email)
                            ->subject('GeoSpace - Application Status')
                            ->html("
                            <h2>Hello {$userName},</h2>
                            <p>Thank you for your interest in joining GeoSpace.</p>
                            <p>Unfortunately, we are unable to approve your application at this time.</p>
                            <p>If you have any questions, please feel free to contact our support team.</p>
                            <br>
                            <p>Best regards,</p>
                            <p>GeoSpace Team</p>
                        ");
                });

                DB::commit();

                return response()->json(
                    MessageHelper::success('User rejected and notification email sent')
                );
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Final verification error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to process verification'),
                500
            );
        }
    }

    /**
     * Get verified users list
     */
    public function verifiedUserList(Request $request): JsonResponse
    {
        try {
            $users = User::with(['userDetails', 'role'])
                         ->where('verification_status', 'verified')
                         ->where('is_active', true)
                         ->orderBy('created_at', 'desc')
                         ->get()
                         ->map(function ($user) {
                             return [
                                 'UserId' => $user->user_id,
                                 'Name' => $user->userDetails
                                     ? $user->userDetails->first_name . ' ' . $user->userDetails->last_name
                                     : 'N/A',
                                 'Email' => $user->email,
                                 'Type' => $user->role->role_name ?? 'N/A',
                                 'Status' => 'Verified',
                                 'CreatedAt' => $user->created_at->format('Y-m-d H:i:s')
                             ];
                         });

            return response()->json(
                MessageHelper::success('Verified users retrieved successfully', $users)
            );

        } catch (\Exception $e) {
            Log::error('Get verified users error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to retrieve verified users'),
                500
            );
        }
    }

    /**
     * Get suspended accounts list
     */
    public function suspendedAccountsList(Request $request): JsonResponse
    {
        try {
            $users = User::with(['userDetails', 'role'])
                         ->where('verification_status', 'rejected')
                         ->orWhere('is_active', false)
                         ->orderBy('created_at', 'desc')
                         ->get()
                         ->map(function ($user) {
                             return [
                                 'UserId' => $user->user_id,
                                 'Name' => $user->userDetails
                                     ? $user->userDetails->first_name . ' ' . $user->userDetails->last_name
                                     : 'N/A',
                                 'Email' => $user->email,
                                 'Type' => $user->role->role_name ?? 'N/A',
                                 'Status' => ucfirst($user->verification_status),
                                 'CreatedAt' => $user->created_at->format('Y-m-d H:i:s')
                             ];
                         });

            return response()->json(
                MessageHelper::success('Suspended accounts retrieved successfully', $users)
            );

        } catch (\Exception $e) {
            Log::error('Get suspended accounts error: ' . $e->getMessage());
            return response()->json(
                MessageHelper::error('Failed to retrieve suspended accounts'),
                500
            );
        }
    }
}
