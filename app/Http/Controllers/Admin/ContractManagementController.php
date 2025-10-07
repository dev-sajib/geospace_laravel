<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class ContractManagementController extends Controller
{
    /**
     * Display a listing of all contracts
     * GET /api/admin/contracts
     */
    public function index()
    {
        try {
//            $perPage = $request->input('per_page', 15);
//            $status = $request->input('status');
//            $companyId = $request->input('company_id');
//            $freelancerId = $request->input('freelancer_id');

            $query = DB::table('contracts as c')
                       ->leftJoin('projects as p', 'c.project_id', '=', 'p.project_id')
                       ->leftJoin('company_details as cd', 'c.company_id', '=', 'cd.company_id')
                       ->leftJoin('users as cu', 'cd.user_id', '=', 'cu.user_id')
                       ->leftJoin('users as fu', 'c.freelancer_id', '=', 'fu.user_id')
                       ->leftJoin('user_details as fud', 'fu.user_id', '=', 'fud.user_id')
                       ->select(
                           'c.*',
                           'p.project_title',
                           'p.location',
                           'p.project_type',
                           'cd.company_name',
                           'cu.email as company_email',
                           'fu.email as freelancer_email',
                           DB::raw("CONCAT(fud.first_name, ' ', fud.last_name) as freelancer_name"),
                           'fud.profile_image as freelancer_image'
                       );
            $contracts = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Contracts retrieved successfully',
                'data' => $contracts
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contracts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified contract with all details
     * GET /api/admin/contracts/{id}
     */
    public function show($id)
    {
        try {
            $contract = DB::table('contracts as c')
                          ->leftJoin('projects as p', 'c.project_id', '=', 'p.project_id')
                          ->leftJoin('company_details as cd', 'c.company_id', '=', 'cd.company_id')
                          ->leftJoin('users as cu', 'cd.user_id', '=', 'cu.user_id')
                          ->leftJoin('users as fu', 'c.freelancer_id', '=', 'fu.user_id')
                          ->leftJoin('user_details as fud', 'fu.user_id', '=', 'fud.user_id')
                          ->select(
                              'c.*',
                              'p.project_title',
                              'p.project_description',
                              'p.project_type',
                              'p.location',
                              'p.is_remote',
                              'cd.company_name',
                              'cd.company_type',
                              'cd.website as company_website',
                              'cd.logo as company_logo',
                              'cu.email as company_email',
                              'fu.email as freelancer_email',
                              DB::raw("CONCAT(fud.first_name, ' ', fud.last_name) as freelancer_name"),
                              'fud.profile_image as freelancer_image',
                              'fud.phone as freelancer_phone',
                              'fud.hourly_rate as freelancer_hourly_rate'
                          )
                          ->where('c.contract_id', $id)
                          ->first();

            if (!$contract) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract not found'
                ], 404);
            }

            // Decode JSON fields
            if ($contract->milestones) {
                $contract->milestones = json_decode($contract->milestones);
            }

            // Get timesheets related to this contract
            $timesheets = DB::table('timesheets')
                            ->where('contract_id', $id)
                            ->orderBy('work_date', 'desc')
                            ->get();

            // Get payments related to this contract
            $payments = DB::table('payments')
                          ->where('contract_id', $id)
                          ->orderBy('created_at', 'desc')
                          ->get();

            // Get invoices related to this contract
            $invoices = DB::table('invoices')
                          ->where('contract_id', $id)
                          ->orderBy('created_at', 'desc')
                          ->get();

            // Get disputes related to this contract
            $disputes = DB::table('dispute_tickets')
                          ->where('contract_id', $id)
                          ->orderBy('created_at', 'desc')
                          ->get();

            return response()->json([
                'success' => true,
                'message' => 'Contract retrieved successfully',
                'data' => [
                    'contract' => $contract,
                    'timesheets' => $timesheets,
                    'payments' => $payments,
                    'invoices' => $invoices,
                    'disputes' => $disputes
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contract',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created contract
     * POST /api/admin/contracts
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|integer|exists:projects,project_id',
            'freelancer_id' => 'required|integer|exists:users,user_id',
            'company_id' => 'required|integer|exists:company_details,company_id',
            'contract_title' => 'required|string|max:255',
            'contract_description' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:Pending,Active,Completed,Cancelled,Disputed',
            'payment_terms' => 'nullable|string|max:255',
            'milestones' => 'nullable|json'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contractId = DB::table('contracts')->insertGetId([
                'project_id' => $request->project_id,
                'freelancer_id' => $request->freelancer_id,
                'company_id' => $request->company_id,
                'contract_title' => $request->contract_title,
                'contract_description' => $request->contract_description,
                'hourly_rate' => $request->hourly_rate,
                'total_amount' => $request->total_amount,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => $request->status ?? 'Pending',
                'payment_terms' => $request->payment_terms,
                'milestones' => $request->milestones,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Contract Created',
                'entity_type' => 'contract',
                'entity_id' => $contractId,
                'new_values' => json_encode($request->all()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            // Create notifications for freelancer and company
            DB::table('notifications')->insert([
                [
                    'user_id' => $request->freelancer_id,
                    'title' => 'New Contract Created',
                    'message' => "A new contract '{$request->contract_title}' has been created for you.",
                    'type' => 'Info',
                    'action_url' => "/contracts/{$contractId}",
                    'created_at' => now()
                ]
            ]);

            $contract = DB::table('contracts')->where('contract_id', $contractId)->first();

            return response()->json([
                'success' => true,
                'message' => 'Contract created successfully',
                'data' => $contract
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create contract',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified contract
     * PUT/PATCH /api/admin/contracts/{id}
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'nullable|integer|exists:projects,project_id',
            'freelancer_id' => 'nullable|integer|exists:users,user_id',
            'company_id' => 'nullable|integer|exists:company_details,company_id',
            'contract_title' => 'nullable|string|max:255',
            'contract_description' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:Pending,Active,Completed,Cancelled,Disputed',
            'payment_terms' => 'nullable|string|max:255',
            'milestones' => 'nullable|json'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contract = DB::table('contracts')->where('contract_id', $id)->first();

            if (!$contract) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract not found'
                ], 404);
            }

            $updateData = array_filter($request->all(), function($value) {
                return $value !== null;
            });
            $updateData['updated_at'] = now();

            DB::table('contracts')->where('contract_id', $id)->update($updateData);

            // Log activity
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Contract Updated',
                'entity_type' => 'contract',
                'entity_id' => $id,
                'old_values' => json_encode($contract),
                'new_values' => json_encode($updateData),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now()
            ]);

            $updatedContract = DB::table('contracts')->where('contract_id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Contract updated successfully',
                'data' => $updatedContract
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update contract',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified contract
     * DELETE /api/admin/contracts/{id}
     */
    public function destroy($id)
    {
        try {
            $contract = DB::table('contracts')->where('contract_id', $id)->first();

            if (!$contract) {
                return response()->json([
                    'success' => false,
                    'message' => 'Contract not found'
                ], 404);
            }

            // Check if contract has dependent records
            $hasTimesheets = DB::table('timesheets')->where('contract_id', $id)->exists();
            $hasPayments = DB::table('payments')->where('contract_id', $id)->exists();

            if ($hasTimesheets || $hasPayments) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete contract with existing timesheets or payments. Please archive it instead.'
                ], 400);
            }

            // Log activity before deletion
            DB::table('activity_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'Contract Deleted',
                'entity_type' => 'contract',
                'entity_id' => $id,
                'old_values' => json_encode($contract),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now()
            ]);

            DB::table('contracts')->where('contract_id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contract deleted successfully'
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete contract',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get contract statistics
     * GET /api/admin/contracts/stats
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_contracts' => DB::table('contracts')->count(),
                'active_contracts' => DB::table('contracts')->where('status', 'Active')->count(),
                'completed_contracts' => DB::table('contracts')->where('status', 'Completed')->count(),
                'pending_contracts' => DB::table('contracts')->where('status', 'Pending')->count(),
                'disputed_contracts' => DB::table('contracts')->where('status', 'Disputed')->count(),
                'total_amount' => DB::table('contracts')->sum('total_amount'),
                'average_contract_value' => DB::table('contracts')->avg('total_amount')
            ];

            return response()->json([
                'success' => true,
                'message' => 'Statistics retrieved successfully',
                'data' => $stats
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
