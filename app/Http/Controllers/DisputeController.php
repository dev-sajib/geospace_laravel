<?php

namespace App\Http\Controllers;

use App\Helpers\MessageHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DisputeController extends Controller
{
    /**
     * Get freelancer's contracts for dispute ticket creation
     *
     * @param int $freelancerId
     * @return JsonResponse
     */
    public function getFreelancerContracts($freelancerId): JsonResponse
    {
        try {
            $contracts = DB::table('contracts as c')
                ->join('projects as p', 'c.project_id', '=', 'p.project_id')
                ->join('company_details as cd', 'c.company_id', '=', 'cd.company_id')
                ->where('c.freelancer_id', $freelancerId)
                ->where('c.status', 'Active')
                ->select(
                    'c.contract_id as ContractId',
                    'c.contract_title as ContractTitle',
                    'c.company_id as CompanyId',
                    'cd.company_name as CompanyName',
                    'c.status as ContractStatus',
                    'p.project_title as ProjectTitle'
                )
                ->get();

            return response()->json([
                'success' => true,
                'data' => $contracts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch contracts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit a dispute ticket
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function submitTicket(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'contract_id' => 'required|integer|exists:contracts,contract_id',
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'nullable|string|max:100',
                'priority' => 'nullable|in:Low,Medium,High,Critical',
                'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $freelancerId = Auth::id();
            
            // Get the "Open" status ID
            $openStatus = DB::table('dispute_status')
                ->where('status_name', 'Open')
                ->first();

            if (!$openStatus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Open status not found'
                ], 500);
            }

            // Handle file upload if present
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $attachmentPath = $file->storeAs('dispute_attachments', $filename, 'public');
            }

            // Generate unique ticket number
            $ticketNumber = 'DT-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Ensure ticket number is unique
            while (DB::table('dispute_tickets')->where('ticket_number', $ticketNumber)->exists()) {
                $ticketNumber = 'DT-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            // Insert the dispute ticket
            $ticketId = DB::table('dispute_tickets')->insertGetId([
                'ticket_number' => $ticketNumber,
                'contract_id' => $request->contract_id,
                'created_by' => $freelancerId,
                'assigned_to' => null, // Will be assigned by admin later
                'status_id' => $openStatus->status_id,
                'priority' => $request->priority ?? 'Medium',
                'category' => $request->category,
                'subject' => $request->subject,
                'description' => $request->description,
                'attachment' => $attachmentPath,
                'resolution' => null,
                'resolved_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dispute ticket submitted successfully',
                'data' => [
                    'ticket_id' => $ticketId
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit dispute ticket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dispute tickets list for admin
     *
     * @return JsonResponse
     */
    public function getDisputeTicketsList(): JsonResponse
    {
        try {
            $tickets = DB::table('dispute_tickets as dt')
                ->join('contracts as c', 'dt.contract_id', '=', 'c.contract_id')
                ->join('projects as p', 'c.project_id', '=', 'p.project_id')
                ->join('company_details as cd', 'c.company_id', '=', 'cd.company_id')
                ->join('user_details as ud', 'dt.created_by', '=', 'ud.user_id')
                ->join('dispute_status as ds', 'dt.status_id', '=', 'ds.status_id')
                ->leftJoin('user_details as agent_ud', 'dt.assigned_to', '=', 'agent_ud.user_id')
                ->select(
                    'dt.ticket_id as TicketId',
                    'dt.ticket_number as TicketNumber',
                    'dt.contract_id as ContractId',
                    'dt.created_by as CreatedBy',
                    'dt.assigned_to as AssignedTo',
                    'dt.status_id as StatusId',
                    'dt.priority as Priority',
                    'dt.category as Category',
                    'dt.subject as Issue',
                    'dt.description as Description',
                    'dt.attachment as Attachment',
                    'dt.resolution as Resolution',
                    'dt.resolved_at as SolveDate',
                    'dt.created_at as SubmissionDate',
                    'ds.status_name as StatusDisplayName',
                    'cd.company_name as CompanyName',
                    DB::raw("CONCAT(ud.first_name, ' ', ud.last_name) as FreelancerName"),
                    DB::raw("CONCAT(agent_ud.first_name, ' ', agent_ud.last_name) as AssignedAgent"),
                    'p.project_title as ProjectTitle'
                )
                ->orderBy('dt.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tickets
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch dispute tickets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update dispute ticket status
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateTicketStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ticket_id' => 'required|integer|exists:dispute_tickets,ticket_id',
                'status_id' => 'required|integer|exists:dispute_status,status_id',
                'updated_by' => 'required|integer|exists:users,user_id'
            ]);

            $ticketId = $request->ticket_id;
            $statusId = $request->status_id;
            $updatedBy = $request->updated_by;

            // Check if status is "Resolved" to set resolved_at
            $status = DB::table('dispute_status')->where('status_id', $statusId)->first();
            $resolvedAt = null;
            if ($status && $status->status_name === 'Resolved') {
                $resolvedAt = now();
            }

            DB::table('dispute_tickets')
                ->where('ticket_id', $ticketId)
                ->update([
                    'status_id' => $statusId,
                    'resolved_at' => $resolvedAt,
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket status updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ticket status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
