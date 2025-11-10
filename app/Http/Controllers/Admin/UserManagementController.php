<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MessageHelper;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class UserManagementController extends \App\Http\Controllers\Controller {
    /**
     * Get verified users list
     */
    public function verifiedUserList( Request $request ): JsonResponse {
        try {
            $users = User::with( [ 'freelancerDetails', 'adminDetails', 'supportDetails', 'companyDetails', 'role' ] )
                         ->where( 'is_active', true )
                         ->where( 'verification_status', 'verified' )
                         ->where( 'role_id', '!=', 1 )
                         ->orderBy( 'created_at', 'desc' )
                         ->get()
                         ->map( function ( $user ) {
                             $userName = 'N/A';

                             // Get name based on user role
                             if ( $user->role_id == 2 && $user->freelancerDetails ) {
                                 $firstName = trim( $user->freelancerDetails->first_name ?? '' );
                                 $lastName  = trim( $user->freelancerDetails->last_name ?? '' );
                                 if ( $firstName || $lastName ) {
                                     $userName = trim( $firstName . ' ' . $lastName );
                                 }
                             } elseif ( $user->role_id == 1 && $user->adminDetails ) {
                                 $firstName = trim( $user->adminDetails->first_name ?? '' );
                                 $lastName  = trim( $user->adminDetails->last_name ?? '' );
                                 if ( $firstName || $lastName ) {
                                     $userName = trim( $firstName . ' ' . $lastName );
                                 }
                             } elseif ( $user->role_id == 4 && $user->supportDetails ) {
                                 $firstName = trim( $user->supportDetails->first_name ?? '' );
                                 $lastName  = trim( $user->supportDetails->last_name ?? '' );
                                 if ( $firstName || $lastName ) {
                                     $userName = trim( $firstName . ' ' . $lastName );
                                 }
                             } elseif ( $user->role_id == 3 && $user->companyDetails && ! empty( $user->companyDetails->company_name ) ) {
                                 $userName = $user->companyDetails->company_name;
                             }

                             if ( $userName === 'N/A' || empty( $userName ) ) {
                                 $userName = $user->email;
                             }

                             return [
                                 'UserId'         => $user->user_id,
                                 'UserName'       => $userName,
                                 'Email'          => $user->email,
                                 'Role'           => $user->role->role_name ?? 'N/A',
                                 'Status'         => $user->is_active ? 'Active' : 'Inactive',
                                 'JoinedDate'     => $user->created_at->format( 'Y-m-d H:i:s' ),
                                 'LastActiveDate' => $user->last_login ? $user->last_login->format( 'Y-m-d H:i:s' ) : 'Never'
                             ];
                         } );

            return response()->json( $users );

        } catch ( \Exception $e ) {
            Log::error( 'Get verified users error: ' . $e->getMessage() );

            return response()->json(
                MessageHelper::error( 'Failed to retrieve verified users' ),
                500
            );
        }
    }

    /**
     * Get pending verification list
     * FIXED: Removed verification_status column references
     */
    public function pendingVerificationList( Request $request ): JsonResponse {
        try {
            $users = User::with( [ 'freelancerDetails', 'adminDetails', 'supportDetails', 'companyDetails', 'role' ] )
                         ->where( 'verification_status', 'pending' )  // FIXED: Only check is_verified
                         ->where( 'is_active', true )      // Must be active to be pending
                         ->where( 'role_id', '!=', 1 )
                         ->orderBy( 'created_at', 'desc' )
                         ->get()
                         ->map( function ( $user ) {
                             $userName = 'N/A';

                             // Get name based on user role
                             if ( $user->role_id == 2 && $user->freelancerDetails ) {
                                 $firstName = trim( $user->freelancerDetails->first_name ?? '' );
                                 $lastName  = trim( $user->freelancerDetails->last_name ?? '' );
                                 if ( $firstName || $lastName ) {
                                     $userName = trim( $firstName . ' ' . $lastName );
                                 }
                             } elseif ( $user->role_id == 1 && $user->adminDetails ) {
                                 $firstName = trim( $user->adminDetails->first_name ?? '' );
                                 $lastName  = trim( $user->adminDetails->last_name ?? '' );
                                 if ( $firstName || $lastName ) {
                                     $userName = trim( $firstName . ' ' . $lastName );
                                 }
                             } elseif ( $user->role_id == 4 && $user->supportDetails ) {
                                 $firstName = trim( $user->supportDetails->first_name ?? '' );
                                 $lastName  = trim( $user->supportDetails->last_name ?? '' );
                                 if ( $firstName || $lastName ) {
                                     $userName = trim( $firstName . ' ' . $lastName );
                                 }
                             } elseif ( $user->role_id == 3 && $user->companyDetails && ! empty( $user->companyDetails->company_name ) ) {
                                 $userName = $user->companyDetails->company_name;
                             }

                             if ( $userName === 'N/A' || empty( $userName ) ) {
                                 $userName = $user->email;
                             }

                             return [
                                 'UserId'      => $user->user_id,
                                 'UserName'    => $userName,
                                 'Email'       => $user->email,
                                 'Type'        => $user->role->role_name ?? 'N/A',
                                 'Status'      => 'Pending',  // FIXED: Always pending if not verified
                                 'SubmittedAt' => $user->created_at->format( 'Y-m-d H:i:s' ),
                                 'HasDetails'  => $user->freelancerDetails !== null || $user->adminDetails !== null || $user->supportDetails !== null || $user->companyDetails !== null,
                                 'Action'      => ( $user->freelancerDetails || $user->adminDetails || $user->supportDetails || $user->companyDetails )
                                     ? 'Review Documents'
                                     : 'Validate Account'
                             ];
                         } );

            return response()->json(
                MessageHelper::success( 'Pending verifications retrieved successfully', $users )
            );

        } catch ( \Exception $e ) {
            Log::error( 'Get pending verifications error: ' . $e->getMessage() );

            return response()->json(
                MessageHelper::error( 'Failed to retrieve pending verifications' ),
                500
            );
        }
    }

    /**
     * Get suspended accounts list
     * FIXED: Only check is_active status
     */
    public function suspendedAccountsList( Request $request ): JsonResponse {
        try {
            $users = User::with( [ 'freelancerDetails', 'adminDetails', 'supportDetails', 'companyDetails', 'role' ] )
                         ->where( 'is_active', false )  // FIXED: Only check is_active
                         ->orderBy( 'created_at', 'desc' )
                         ->get()
                         ->map( function ( $user ) {
                             $userName = 'N/A';

                             // Get name based on user role
                             if ( $user->role_id == 2 && $user->freelancerDetails ) {
                                 $firstName = trim( $user->freelancerDetails->first_name ?? '' );
                                 $lastName  = trim( $user->freelancerDetails->last_name ?? '' );
                                 if ( $firstName || $lastName ) {
                                     $userName = trim( $firstName . ' ' . $lastName );
                                 }
                             } elseif ( $user->role_id == 1 && $user->adminDetails ) {
                                 $firstName = trim( $user->adminDetails->first_name ?? '' );
                                 $lastName  = trim( $user->adminDetails->last_name ?? '' );
                                 if ( $firstName || $lastName ) {
                                     $userName = trim( $firstName . ' ' . $lastName );
                                 }
                             } elseif ( $user->role_id == 4 && $user->supportDetails ) {
                                 $firstName = trim( $user->supportDetails->first_name ?? '' );
                                 $lastName  = trim( $user->supportDetails->last_name ?? '' );
                                 if ( $firstName || $lastName ) {
                                     $userName = trim( $firstName . ' ' . $lastName );
                                 }
                             } elseif ( $user->role_id == 3 && $user->companyDetails && ! empty( $user->companyDetails->company_name ) ) {
                                 $userName = $user->companyDetails->company_name;
                             }

                             if ( $userName === 'N/A' || empty( $userName ) ) {
                                 $userName = $user->email;
                             }

                             return [
                                 'UserId'    => $user->user_id,
                                 'Name'      => $userName,
                                 'Email'     => $user->email,
                                 'Type'      => $user->role->role_name ?? 'N/A',
                                 'Status'    => 'Suspended',  // FIXED: Always suspended if not active
                                 'CreatedAt' => $user->created_at->format( 'Y-m-d H:i:s' )
                             ];
                         } );

            return response()->json(
                MessageHelper::success( 'Suspended accounts retrieved successfully', $users )
            );

        } catch ( \Exception $e ) {
            Log::error( 'Get suspended accounts error: ' . $e->getMessage() );

            return response()->json(
                MessageHelper::error( 'Failed to retrieve suspended accounts' ),
                500
            );
        }
    }

    /**
     * Get user details for review
     * FIXED: Removed verification_status
     */
    public function getUserDetails( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'UserId' => 'required|integer|exists:users,user_id'
            ] );

            if ( $validator->fails() ) {
                return response()->json(
                    MessageHelper::validationError( $validator->errors()->toArray() ),
                    422
                );
            }

            $userId = $request->input( 'UserId' );

            $user = User::with( [ 'userDetails', 'companyDetails', 'role' ] )
                        ->where( 'user_id', $userId )
                        ->first();

            if ( ! $user ) {
                return response()->json(
                    MessageHelper::notFound( 'User not found' ),
                    404
                );
            }

            $companyDetails = $user->companyDetails;

            // Base user data
            $userData = [
                'UserId'       => $user->user_id,
                'Email'        => $user->email,
                'UserPosition' => $user->position,
                'RoleId'       => $user->role_id,
                'RoleName'     => $user->role->role_name ?? 'N/A',
                'IsVerified'   => $user->is_verified,
                'IsActive'     => $user->is_active,
                'CreatedAt'    => $user->created_at->format( 'Y-m-d H:i:s' )
            ];

            // Add freelancer-specific details if role is Freelancer (role_id = 2)
            if ($user->role_id == 2) {
                $details = $user->freelancerDetails;
                $userData = array_merge($userData, [
                    'FirstName'    => $details?->first_name ?? 'N/A',
                    'LastName'     => $details?->last_name ?? 'N/A',
                    'Phone'        => $details?->phone ?? 'N/A',
                    'Country'      => $details?->country ?? 'N/A',
                    'City'         => $details?->city ?? 'N/A',
                    'Address'      => $details?->address ?? 'N/A',
                    'PostalCode'   => $details?->postal_code ?? 'N/A',
                    'ProfileImage' => ($details && $details->profile_image)
                        ? env( 'APP_URL' ) . '/' . $details->profile_image
                        : null,
                    'ResumeCV'     => ($details && $details->resume_or_cv)
                        ? env( 'APP_URL' ) . '/' . $details->resume_or_cv
                        : null,
                    'HourlyRate'   => $details?->hourly_rate ?? 0,
                    'LinkedInUrl'  => $details?->linkedin_url ?? 'N/A',
                    'Bio'          => $details?->bio ?? 'N/A'
                ]);
            }

            // Add admin-specific details if role is Admin (role_id = 1)
            if ($user->role_id == 1) {
                $details = $user->adminDetails;
                $userData = array_merge($userData, [
                    'FirstName'    => $details?->first_name ?? 'N/A',
                    'LastName'     => $details?->last_name ?? 'N/A',
                    'Phone'        => $details?->phone ?? 'N/A'
                ]);
            }

            // Add support-specific details if role is Support (role_id = 4)
            if ($user->role_id == 4) {
                $details = $user->supportDetails;
                $userData = array_merge($userData, [
                    'FirstName'    => $details?->first_name ?? 'N/A',
                    'LastName'     => $details?->last_name ?? 'N/A',
                    'Phone'        => $details?->phone ?? 'N/A'
                ]);
            }

            // Add company-specific details if role is Company (role_id = 3)
            if ($user->role_id == 3) {
                $userData = array_merge($userData, [
                    'CompanyName'  => $companyDetails?->company_name ?? 'N/A',
                    'CompanyType'  => $companyDetails?->company_type ?? 'N/A',
                    'Industry'     => $companyDetails?->industry ?? 'N/A',
                    'CompanySize'  => $companyDetails?->company_size ?? 'N/A',
                    'Website'      => $companyDetails?->website ?? 'N/A',
                    'Description'  => $companyDetails?->description ?? 'N/A',
                    'FoundedYear'  => $companyDetails?->founded_year ?? 'N/A',
                    'Headquarters' => $companyDetails?->headquarters ?? 'N/A',
                    'Logo'         => ($companyDetails && $companyDetails->logo)
                        ? env( 'APP_URL' ) . '/' . $companyDetails->logo
                        : null
                ]);
            }

            return response()->json(
                MessageHelper::success( 'User details retrieved successfully', $userData )
            );

        } catch ( \Exception $e ) {
            Log::error( 'Get user details error: ' . $e->getMessage() );

            return response()->json(
                MessageHelper::error( 'Failed to retrieve user details' ),
                500
            );
        }
    }

    /**
     * Send verification request email
     */
    public function sendVerificationRequest( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'UserId' => 'required|integer|exists:users,user_id'
            ] );

            if ( $validator->fails() ) {
                return response()->json(
                    MessageHelper::validationError( $validator->errors()->toArray() ),
                    422
                );
            }

            $user = User::find( $request->input( 'UserId' ) );

            try {
                Mail::send( 'emails.verification_pending', [ 'user' => $user ], function ( $message ) use ( $user ) {
                    $message->to( $user->email )
                            ->subject( 'Account Verification In Progress' );
                } );
            } catch ( \Exception $mailException ) {
                Log::error( 'Failed to send verification email: ' . $mailException->getMessage() );
            }

            return response()->json(
                MessageHelper::success( 'Verification request submitted successfully' )
            );

        } catch ( \Exception $e ) {
            Log::error( 'Send verification request error: ' . $e->getMessage() );

            return response()->json(
                MessageHelper::error( 'Failed to process verification request' ),
                500
            );
        }
    }

    /**
     * Final verification (admin approves/rejects)
     * FIXED: Only update is_verified and is_active
     */
    public function finalVerification( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'UserId' => 'required|integer|exists:users,user_id',
                'Status' => 'required|in:verified,rejected',
                'Reason' => 'nullable|string'
            ] );

            if ( $validator->fails() ) {
                return response()->json(
                    MessageHelper::validationError( $validator->errors()->toArray() ),
                    422
                );
            }

            $user   = User::find( $request->input( 'UserId' ) );
            $status = $request->input( 'Status' );

            // Update user status - FIXED
            $user->is_verified = ( $status === 'verified' );
            $user->is_active   = ( $status === 'verified' );
            if ( $status === 'verified' ) {
                $user->email_verified_at = now();
            }
            $user->save();

            // Send email notification
            try {
                if ( $status === 'verified' ) {
                    Mail::send( 'emails.account_verified', [ 'user' => $user ], function ( $message ) use ( $user ) {
                        $message->to( $user->email )
                                ->subject( 'Account Verified - Welcome to GeoSpace!' );
                    } );
                } else {
                    $reason = $request->input( 'Reason', 'Your account did not meet our verification requirements.' );
                    Mail::send( 'emails.account_rejected', [
                        'user'   => $user,
                        'reason' => $reason
                    ], function ( $message ) use ( $user ) {
                        $message->to( $user->email )
                                ->subject( 'Account Verification Status' );
                    } );
                }
            } catch ( \Exception $mailException ) {
                Log::error( 'Failed to send verification status email: ' . $mailException->getMessage() );
            }

            return response()->json(
                MessageHelper::success( 'User verification status updated successfully' )
            );

        } catch ( \Exception $e ) {
            Log::error( 'Final verification error: ' . $e->getMessage() );

            return response()->json(
                MessageHelper::error( 'Failed to process verification' ),
                500
            );
        }
    }

    public function updateUserStatus( Request $request ): JsonResponse {
        try {
            $validator = Validator::make( $request->all(), [
                'UserId'   => 'required|integer|exists:users,user_id',
                'IsActive' => 'required|boolean',
                'Reason'   => 'nullable|string'
            ] );

            if ( $validator->fails() ) {
                return response()->json(
                    MessageHelper::validationError( $validator->errors()->toArray() ),
                    422
                );
            }

            $user     = User::find( $request->input( 'UserId' ) );
            $isActive = $request->input( 'IsActive' );
            $reason   = $request->input( 'Reason' );

            // Update user status
            $user->is_active = $isActive;
            $user->save();

            // Send email notification
            try {
                if ( $isActive ) {
                    Mail::send( 'emails.account_activated', [ 'user' => $user ], function ( $message ) use ( $user ) {
                        $message->to( $user->email )
                                ->subject( 'Account Activated' );
                    } );
                } else {
                    Mail::send( 'emails.account_suspended', [
                        'user'   => $user,
                        'reason' => $reason
                    ], function ( $message ) use ( $user ) {
                        $message->to( $user->email )
                                ->subject( 'Account Suspended' );
                    } );
                }
            } catch ( \Exception $mailException ) {
                Log::error( 'Failed to send status update email: ' . $mailException->getMessage() );
            }

            return response()->json(
                MessageHelper::success( 'User status updated successfully' )
            );

        } catch ( \Exception $e ) {
            Log::error( 'Update user status error: ' . $e->getMessage() );

            return response()->json(
                MessageHelper::error( 'Failed to update user status' ),
                500
            );
        }
    }

    /**
     * Delete user (soft delete or permanent delete based on requirements)
     */
    public function deleteUser( Request $request, $userId ): JsonResponse {
        try {
            $validator = Validator::make( [ 'user_id' => $userId ], [
                'user_id' => 'required|integer|exists:users,user_id'
            ] );

            if ( $validator->fails() ) {
                return response()->json(
                    MessageHelper::validationError( $validator->errors()->toArray() ),
                    422
                );
            }

            $user = User::find( $userId );

            if ( ! $user ) {
                return response()->json(
                    MessageHelper::notFound( 'User not found' ),
                    404
                );
            }

            // Check if user is admin
            if ( $user->role_id === 1 ) {
                return response()->json(
                    MessageHelper::error( 'Cannot delete admin users' ),
                    403
                );
            }

            DB::beginTransaction();

            try {
                // Delete user (cascading deletes will handle related records)
                $user->delete();

                DB::commit();

                return response()->json(
                    MessageHelper::success( 'User deleted successfully' )
                );

            } catch ( \Exception $e ) {
                DB::rollBack();
                throw $e;
            }

        } catch ( \Exception $e ) {
            Log::error( 'Delete user error: ' . $e->getMessage() );

            return response()->json(
                MessageHelper::error( 'Failed to delete user' ),
                500
            );
        }
    }
}
