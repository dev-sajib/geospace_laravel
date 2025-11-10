<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Helpers\MessageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class BankInformationController extends Controller
{
    /**
     * Get freelancer bank information
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            // Get freelancer_detail_id from user_id
            $freelancerDetail = DB::table('freelancer_details')
                ->where('user_id', $userId)
                ->first();

            if (!$freelancerDetail) {
                return response()->json(
                    MessageHelper::error('Freelancer profile not found'),
                    404
                );
            }

            // Get bank accounts
            $bankAccounts = DB::table('freelancer_bank_information')
                ->where('freelancer_id', $freelancerDetail->freelancer_detail_id)
                ->orderBy('is_primary', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($bank) {
                    return [
                        'bank_info_id' => $bank->bank_info_id,
                        'bank_name' => $bank->bank_name,
                        'account_holder_name' => $bank->account_holder_name,
                        'account_number' => $bank->account_number,
                        'account_type' => $bank->account_type,
                        'routing_number' => $bank->routing_number,
                        'swift_code' => $bank->swift_code,
                        'iban' => $bank->iban,
                        'bank_address' => $bank->bank_address,
                        'bank_city' => $bank->bank_city,
                        'bank_state' => $bank->bank_state,
                        'bank_country' => $bank->bank_country,
                        'bank_postal_code' => $bank->bank_postal_code,
                        'intermediate_bank_name' => $bank->intermediate_bank_name,
                        'intermediate_swift_code' => $bank->intermediate_swift_code,
                        'currency' => $bank->currency,
                        'is_primary' => (bool) $bank->is_primary,
                        'is_verified' => (bool) $bank->is_verified,
                        'verification_document' => $bank->verification_document,
                        'notes' => $bank->notes,
                        'status' => $bank->status,
                        'created_at' => $bank->created_at,
                        'updated_at' => $bank->updated_at
                    ];
                });

            return response()->json(
                MessageHelper::success('Bank information retrieved successfully', $bankAccounts),
                200
            );

        } catch (Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Store a new bank account
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            // Get freelancer_detail_id from user_id
            $freelancerDetail = DB::table('freelancer_details')
                ->where('user_id', $userId)
                ->first();

            if (!$freelancerDetail) {
                return response()->json(
                    MessageHelper::error('Freelancer profile not found'),
                    404
                );
            }

            $validator = Validator::make($request->all(), [
                'bank_name' => 'required|string|max:255',
                'account_holder_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:50',
                'account_type' => 'required|in:Checking,Savings,Business',
                'routing_number' => 'nullable|string|max:30',
                'swift_code' => 'nullable|string|max:20',
                'iban' => 'nullable|string|max:50',
                'bank_address' => 'nullable|string',
                'bank_city' => 'nullable|string|max:100',
                'bank_state' => 'nullable|string|max:100',
                'bank_country' => 'required|string|max:100',
                'bank_postal_code' => 'nullable|string|max:20',
                'intermediate_bank_name' => 'nullable|string|max:255',
                'intermediate_swift_code' => 'nullable|string|max:20',
                'currency' => 'required|string|size:3',
                'is_primary' => 'boolean',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::error($validator->errors()->first()),
                    422
                );
            }

            DB::beginTransaction();

            // If this is set as primary, unset other primary banks
            if ($request->is_primary) {
                DB::table('freelancer_bank_information')
                    ->where('freelancer_id', $freelancerDetail->freelancer_detail_id)
                    ->update(['is_primary' => false]);
            }

            $bankData = [
                'freelancer_id' => $freelancerDetail->freelancer_detail_id,
                'bank_name' => $request->bank_name,
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'account_type' => $request->account_type,
                'routing_number' => $request->routing_number,
                'swift_code' => $request->swift_code,
                'iban' => $request->iban,
                'bank_address' => $request->bank_address,
                'bank_city' => $request->bank_city,
                'bank_state' => $request->bank_state,
                'bank_country' => $request->bank_country,
                'bank_postal_code' => $request->bank_postal_code,
                'intermediate_bank_name' => $request->intermediate_bank_name,
                'intermediate_swift_code' => $request->intermediate_swift_code,
                'currency' => $request->currency,
                'is_primary' => $request->is_primary ?? false,
                'notes' => $request->notes,
                'status' => 'Pending_Verification',
                'created_at' => now(),
                'updated_at' => now()
            ];

            $bankId = DB::table('freelancer_bank_information')->insertGetId($bankData);

            // Get the created bank account
            $newBank = DB::table('freelancer_bank_information')
                ->where('bank_info_id', $bankId)
                ->first();

            $responseData = [
                'bank_info_id' => $newBank->bank_info_id,
                'bank_name' => $newBank->bank_name,
                'account_holder_name' => $newBank->account_holder_name,
                'account_number' => $newBank->account_number,
                'account_type' => $newBank->account_type,
                'routing_number' => $newBank->routing_number,
                'swift_code' => $newBank->swift_code,
                'iban' => $newBank->iban,
                'bank_address' => $newBank->bank_address,
                'bank_city' => $newBank->bank_city,
                'bank_state' => $newBank->bank_state,
                'bank_country' => $newBank->bank_country,
                'bank_postal_code' => $newBank->bank_postal_code,
                'intermediate_bank_name' => $newBank->intermediate_bank_name,
                'intermediate_swift_code' => $newBank->intermediate_swift_code,
                'currency' => $newBank->currency,
                'is_primary' => (bool) $newBank->is_primary,
                'is_verified' => (bool) $newBank->is_verified,
                'verification_document' => $newBank->verification_document,
                'notes' => $newBank->notes,
                'status' => $newBank->status,
                'created_at' => $newBank->created_at,
                'updated_at' => $newBank->updated_at
            ];

            DB::commit();

            return response()->json(
                MessageHelper::success('Bank account added successfully', $responseData),
                201
            );

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Update bank account
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            // Get freelancer_detail_id from user_id
            $freelancerDetail = DB::table('freelancer_details')
                ->where('user_id', $userId)
                ->first();

            if (!$freelancerDetail) {
                return response()->json(
                    MessageHelper::error('Freelancer profile not found'),
                    404
                );
            }

            // Check if bank account exists and belongs to this freelancer
            $existingBank = DB::table('freelancer_bank_information')
                ->where('bank_info_id', $id)
                ->where('freelancer_id', $freelancerDetail->freelancer_detail_id)
                ->first();

            if (!$existingBank) {
                return response()->json(
                    MessageHelper::error('Bank account not found'),
                    404
                );
            }

            $validator = Validator::make($request->all(), [
                'bank_name' => 'required|string|max:255',
                'account_holder_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:50',
                'account_type' => 'required|in:Checking,Savings,Business',
                'routing_number' => 'nullable|string|max:30',
                'swift_code' => 'nullable|string|max:20',
                'iban' => 'nullable|string|max:50',
                'bank_address' => 'nullable|string',
                'bank_city' => 'nullable|string|max:100',
                'bank_state' => 'nullable|string|max:100',
                'bank_country' => 'required|string|max:100',
                'bank_postal_code' => 'nullable|string|max:20',
                'intermediate_bank_name' => 'nullable|string|max:255',
                'intermediate_swift_code' => 'nullable|string|max:20',
                'currency' => 'required|string|size:3',
                'is_primary' => 'boolean',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    MessageHelper::error($validator->errors()->first()),
                    422
                );
            }

            DB::beginTransaction();

            // If this is set as primary, unset other primary banks
            if ($request->is_primary) {
                DB::table('freelancer_bank_information')
                    ->where('freelancer_id', $freelancerDetail->freelancer_detail_id)
                    ->where('bank_info_id', '!=', $id)
                    ->update(['is_primary' => false]);
            }

            $updateData = [
                'bank_name' => $request->bank_name,
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'account_type' => $request->account_type,
                'routing_number' => $request->routing_number,
                'swift_code' => $request->swift_code,
                'iban' => $request->iban,
                'bank_address' => $request->bank_address,
                'bank_city' => $request->bank_city,
                'bank_state' => $request->bank_state,
                'bank_country' => $request->bank_country,
                'bank_postal_code' => $request->bank_postal_code,
                'intermediate_bank_name' => $request->intermediate_bank_name,
                'intermediate_swift_code' => $request->intermediate_swift_code,
                'currency' => $request->currency,
                'is_primary' => $request->is_primary ?? false,
                'notes' => $request->notes,
                'updated_at' => now()
            ];

            DB::table('freelancer_bank_information')
                ->where('bank_info_id', $id)
                ->where('freelancer_id', $freelancerDetail->freelancer_detail_id)
                ->update($updateData);

            // Get the updated bank account
            $updatedBank = DB::table('freelancer_bank_information')
                ->where('bank_info_id', $id)
                ->first();

            $responseData = [
                'bank_info_id' => $updatedBank->bank_info_id,
                'bank_name' => $updatedBank->bank_name,
                'account_holder_name' => $updatedBank->account_holder_name,
                'account_number' => $updatedBank->account_number,
                'account_type' => $updatedBank->account_type,
                'routing_number' => $updatedBank->routing_number,
                'swift_code' => $updatedBank->swift_code,
                'iban' => $updatedBank->iban,
                'bank_address' => $updatedBank->bank_address,
                'bank_city' => $updatedBank->bank_city,
                'bank_state' => $updatedBank->bank_state,
                'bank_country' => $updatedBank->bank_country,
                'bank_postal_code' => $updatedBank->bank_postal_code,
                'intermediate_bank_name' => $updatedBank->intermediate_bank_name,
                'intermediate_swift_code' => $updatedBank->intermediate_swift_code,
                'currency' => $updatedBank->currency,
                'is_primary' => (bool) $updatedBank->is_primary,
                'is_verified' => (bool) $updatedBank->is_verified,
                'verification_document' => $updatedBank->verification_document,
                'notes' => $updatedBank->notes,
                'status' => $updatedBank->status,
                'created_at' => $updatedBank->created_at,
                'updated_at' => $updatedBank->updated_at
            ];

            DB::commit();

            return response()->json(
                MessageHelper::success('Bank account updated successfully', $responseData),
                200
            );

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Delete bank account
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            // Get freelancer_detail_id from user_id
            $freelancerDetail = DB::table('freelancer_details')
                ->where('user_id', $userId)
                ->first();

            if (!$freelancerDetail) {
                return response()->json(
                    MessageHelper::error('Freelancer profile not found'),
                    404
                );
            }

            $deleted = DB::table('freelancer_bank_information')
                ->where('bank_info_id', $id)
                ->where('freelancer_id', $freelancerDetail->freelancer_detail_id)
                ->delete();

            if (!$deleted) {
                return response()->json(
                    MessageHelper::error('Bank account not found'),
                    404
                );
            }

            return response()->json(
                MessageHelper::success('Bank account deleted successfully'),
                200
            );

        } catch (Exception $e) {
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }

    /**
     * Set bank account as primary
     */
    public function setPrimary(Request $request, int $id): JsonResponse
    {
        try {
            $userId = $request->user()->user_id;

            // Get freelancer_detail_id from user_id
            $freelancerDetail = DB::table('freelancer_details')
                ->where('user_id', $userId)
                ->first();

            if (!$freelancerDetail) {
                return response()->json(
                    MessageHelper::error('Freelancer profile not found'),
                    404
                );
            }

            // Check if bank account exists and belongs to this freelancer
            $bankAccount = DB::table('freelancer_bank_information')
                ->where('bank_info_id', $id)
                ->where('freelancer_id', $freelancerDetail->freelancer_detail_id)
                ->first();

            if (!$bankAccount) {
                return response()->json(
                    MessageHelper::error('Bank account not found'),
                    404
                );
            }

            DB::beginTransaction();

            // Unset all primary banks for this freelancer
            DB::table('freelancer_bank_information')
                ->where('freelancer_id', $freelancerDetail->freelancer_detail_id)
                ->update(['is_primary' => false]);

            // Set this bank as primary
            DB::table('freelancer_bank_information')
                ->where('bank_info_id', $id)
                ->update(['is_primary' => true, 'updated_at' => now()]);

            DB::commit();

            return response()->json(
                MessageHelper::success('Primary bank account updated successfully'),
                200
            );

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(
                MessageHelper::error('An error occurred: ' . $e->getMessage()),
                500
            );
        }
    }
}