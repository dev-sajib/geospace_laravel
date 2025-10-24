<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoSupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminVideoSupportController extends Controller
{
    /**
     * Get all video support requests for admin
     */
    public function index()
    {
        try {
            $requests = VideoSupportRequest::with(['freelancer.userDetails'])
                ->orderBy('meeting_date', 'desc')
                ->orderBy('meeting_time', 'desc')
                ->get();

            // Format data for frontend
            $formattedRequests = $requests->map(function ($request) {
                $firstName = $request->freelancer->userDetails->first_name ?? '';
                $lastName = $request->freelancer->userDetails->last_name ?? '';
                $senderName = trim($firstName . ' ' . $lastName) ?: 'Unknown User';

                return [
                    'request_id' => $request->request_id,
                    'meetingDate' => $request->meeting_date->format('Y-m-d'),
                    'meetingDateFormatted' => $request->meeting_date->format('F d, Y'),
                    'meetingTime' => date('g:i A', strtotime($request->meeting_time)),
                    'meetingTimeRaw' => $request->meeting_time,
                    'senderName' => $senderName,
                    'senderRole' => 'Freelancer',
                    'freelancerId' => $request->freelancer_id,
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
            Log::error('Error fetching admin video support requests: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch video support requests'
            ], 500);
        }
    }

    /**
     * Update video support request (add link, change status, etc.)
     */
    public function update(Request $request, $requestId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'video_link' => 'nullable|url|max:255',
                'status' => 'required|in:Open,Scheduled,Completed,Cancelled',
                'meeting_date' => 'nullable|date',
                'meeting_time' => 'nullable|date_format:H:i',
                'notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $videoRequest = VideoSupportRequest::findOrFail($requestId);

            // Update fields
            if ($request->has('video_link')) {
                $videoRequest->video_link = $request->video_link;
            }

            if ($request->has('status')) {
                $videoRequest->status = $request->status;
            }

            if ($request->has('meeting_date')) {
                $videoRequest->meeting_date = $request->meeting_date;
            }

            if ($request->has('meeting_time')) {
                $videoRequest->meeting_time = $request->meeting_time;
            }

            if ($request->has('notes')) {
                $videoRequest->notes = $request->notes;
            }

            $videoRequest->save();

            return response()->json([
                'success' => true,
                'message' => 'Video support request updated successfully',
                'data' => [
                    'request_id' => $videoRequest->request_id,
                    'video_link' => $videoRequest->video_link,
                    'status' => $videoRequest->status,
                    'meeting_date' => $videoRequest->meeting_date->format('Y-m-d'),
                    'meeting_time' => date('g:i A', strtotime($videoRequest->meeting_time)),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating video support request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update video support request'
            ], 500);
        }
    }

    /**
     * Get a specific video support request
     */
    public function show($requestId)
    {
        try {
            $request = VideoSupportRequest::with(['freelancer.userDetails'])
                ->findOrFail($requestId);

            $firstName = $request->freelancer->userDetails->first_name ?? '';
            $lastName = $request->freelancer->userDetails->last_name ?? '';
            $freelancerName = trim($firstName . ' ' . $lastName) ?: 'Unknown User';

            return response()->json([
                'success' => true,
                'data' => [
                    'request_id' => $request->request_id,
                    'freelancer_id' => $request->freelancer_id,
                    'freelancer_name' => $freelancerName,
                    'meeting_date' => $request->meeting_date->format('Y-m-d'),
                    'meeting_time' => $request->meeting_time,
                    'video_link' => $request->video_link,
                    'status' => $request->status,
                    'notes' => $request->notes,
                    'created_at' => $request->created_at->format('Y-m-d H:i:s'),
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching video support request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Video support request not found'
            ], 404);
        }
    }
}
