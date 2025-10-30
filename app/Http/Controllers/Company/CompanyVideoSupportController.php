<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\VideoSupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompanyVideoSupportController extends Controller
{
    /**
     * Get all video support requests for the authenticated company
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $companyId = $user->user_id;

            $requests = VideoSupportRequest::where('company_id', $companyId)
                ->orderBy('meeting_date', 'desc')
                ->orderBy('meeting_time', 'desc')
                ->get();

            // Format data for frontend
            $formattedRequests = $requests->map(function ($request) {
                return [
                    'request_id' => $request->request_id,
                    'dateApplied' => $request->created_at->format('F d, Y'),
                    'meeting_date' => $request->meeting_date->format('Y-m-d'),
                    'meeting_date_formatted' => $request->meeting_date->format('F d, Y'),
                    'time' => date('g:i A', strtotime($request->meeting_time)),
                    'meeting_time' => $request->meeting_time,
                    'videoLink' => $request->video_link,
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'created_at' => $request->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedRequests,
                'total' => $requests->count()
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching company video support requests: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch video support requests'
            ], 500);
        }
    }

    /**
     * Create a new video support request for company
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'meeting_date' => 'required|date|after_or_equal:today',
                'meeting_time' => 'required|date_format:H:i',
                'notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $companyId = $user->user_id;

            $videoRequest = VideoSupportRequest::create([
                'company_id' => $companyId,
                'freelancer_id' => null,
                'meeting_date' => $request->meeting_date,
                'meeting_time' => $request->meeting_time,
                'notes' => $request->notes,
                'status' => 'Open',
                'video_link' => null, // Will be added by admin later
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video support request submitted successfully',
                'data' => [
                    'request_id' => $videoRequest->request_id,
                    'dateApplied' => $videoRequest->created_at->format('F d, Y'),
                    'meeting_date' => $videoRequest->meeting_date->format('Y-m-d'),
                    'meeting_date_formatted' => $videoRequest->meeting_date->format('F d, Y'),
                    'time' => date('g:i A', strtotime($videoRequest->meeting_time)),
                    'meeting_time' => $videoRequest->meeting_time,
                    'videoLink' => $videoRequest->video_link,
                    'status' => $videoRequest->status,
                    'notes' => $videoRequest->notes,
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating company video support request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create video support request'
            ], 500);
        }
    }

    /**
     * Get a specific video support request for company
     */
    public function show($requestId)
    {
        try {
            $user = Auth::user();
            $companyId = $user->user_id;

            $request = VideoSupportRequest::where('request_id', $requestId)
                ->where('company_id', $companyId)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => [
                    'request_id' => $request->request_id,
                    'dateApplied' => $request->created_at->format('F d, Y'),
                    'meeting_date' => $request->meeting_date->format('Y-m-d'),
                    'meeting_date_formatted' => $request->meeting_date->format('F d, Y'),
                    'time' => date('g:i A', strtotime($request->meeting_time)),
                    'meeting_time' => $request->meeting_time,
                    'videoLink' => $request->video_link,
                    'status' => $request->status,
                    'notes' => $request->notes,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching company video support request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Video support request not found'
            ], 404);
        }
    }
}
