<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Models\Admin;
use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Events\ConversationUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Get all conversations for admin dashboard.
     */
    public function getConversations(Request $request): JsonResponse
    {
        try {
            $query = Conversation::with(['participants.participant', 'latestMessage']);

            // Apply filters
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('subject', 'like', "%{$search}%")
                      ->orWhere('purpose', 'like', "%{$search}%");
                });
            }

            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $conversations = $query->orderBy('last_message_at', 'desc')
                                 ->paginate($perPage);

            $conversationsData = $conversations->map(function ($conversation) {
                $customerParticipant = $conversation->customer()->first();
                $supportAgentParticipant = $conversation->supportAgent()->first();
                $latestMessage = $conversation->latestMessage()->first();

                return [
                    'id' => $conversation->id,
                    'subject' => $conversation->subject,
                    'purpose' => $conversation->purpose,
                    'attachment_path' => $conversation->attachment_path,
                    'status' => $conversation->status,
                    'customer_name' => $this->getCustomerName($customerParticipant),
                    'customer_id' => $customerParticipant?->participant_id,
                    'support_agent_name' => $supportAgentParticipant?->participant?->name ?? $supportAgentParticipant?->participant?->UserName ?? null,
                    'support_agent_id' => $supportAgentParticipant?->participant_id,
                    'unread_count' => $supportAgentParticipant?->unread_count ?? 0,
                    'last_message_at' => $conversation->last_message_at?->toISOString(),
                    'latest_message_preview' => $latestMessage?->content ? substr($latestMessage->content, 0, 100) . '...' : null,
                    'created_at' => $conversation->created_at->toISOString(),
                ];
            });

            return response()->json([
                'success' => true,
                'conversations' => $conversationsData,
                'pagination' => [
                    'current_page' => $conversations->currentPage(),
                    'last_page' => $conversations->lastPage(),
                    'per_page' => $conversations->perPage(),
                    'total' => $conversations->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch conversations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get messages for a conversation (admin view).
     */
    public function getMessages(Request $request, $conversationId): JsonResponse
    {
        try {
            $conversation = Conversation::with(['participants.participant'])->find($conversationId);
            
            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversation not found'
                ], 404);
            }

            $messages = Message::where('conversation_id', $conversationId)
                ->with('sender')
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark messages as read for the admin
            $admin = Auth::user();
            $adminParticipant = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $admin->user_id)
                ->where('participant_type', get_class($admin))
                ->first();

            if ($adminParticipant) {
                $adminParticipant->markAsRead();
            }

            $messagesData = $messages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    'sender_name' => $this->getSenderName($message->sender),
                    'content' => $message->content,
                    'message_type' => $message->message_type,
                    'attachment_path' => $message->attachment_path,
                    'attachment_name' => $message->attachment_name,
                    'is_read' => $message->is_read,
                    'created_at' => $message->created_at->toISOString(),
                    'formatted_time' => $message->formatted_time,
                ];
            });

            // Get conversation details
            $customerParticipant = $conversation->customer()->first();
            $conversationData = [
                'id' => $conversation->id,
                'subject' => $conversation->subject,
                'purpose' => $conversation->purpose,
                'attachment_path' => $conversation->attachment_path,
                'status' => $conversation->status,
                'customer_name' => $this->getCustomerName($customerParticipant),
                'customer_id' => $customerParticipant?->participant_id,
                'created_at' => $conversation->created_at->toISOString(),
            ];

            return response()->json([
                'success' => true,
                'conversation' => $conversationData,
                'messages' => $messagesData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch messages',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign conversation to admin and start chatting.
     */
    public function startChatting(Request $request, $conversationId): JsonResponse
    {
        try {
            $admin = Auth::user();
            $conversation = Conversation::find($conversationId);
            
            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversation not found'
                ], 404);
            }

            // Check if admin is already assigned
            $existingParticipant = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $admin->user_id)
                ->where('participant_type', get_class($admin))
                ->first();

            if (!$existingParticipant) {
                // Assign admin to conversation
                ConversationParticipant::create([
                    'conversation_id' => $conversationId,
                    'participant_id' => $admin->user_id,
                    'participant_type' => get_class($admin),
                    'role' => 'support_agent',
                ]);
            }

            // Auto-change status from 'open' to 'in_progress' when admin opens the conversation
            if ($conversation->status === 'open') {
                $conversation->update(['status' => 'in_progress']);

                // Broadcast conversation update
                broadcast(new ConversationUpdated($conversation));
            }

            // Get conversation details
            $customerParticipant = $conversation->customer()->first();
            $conversationData = [
                'id' => $conversation->id,
                'subject' => $conversation->subject,
                'purpose' => $conversation->purpose,
                'attachment_path' => $conversation->attachment_path,
                'status' => $conversation->status,
                'customer_name' => $this->getCustomerName($customerParticipant),
                'customer_id' => $customerParticipant?->participant_id,
                'created_at' => $conversation->created_at->toISOString(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Chat started successfully',
                'conversation' => $conversationData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start chat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send a message (admin).
     */
    public function sendMessage(Request $request, $conversationId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string|max:2000',
            'attachment' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validate that at least content or attachment is provided
        if (empty($request->content) && !$request->hasFile('attachment')) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => ['message' => ['Please provide either a message or an attachment']]
            ], 422);
        }

        try {
            $admin = Auth::user();
            
            // Check if admin is participant
            $participant = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $admin->user_id)
                ->where('participant_type', get_class($admin))
                ->first();

            if (!$participant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $conversation = Conversation::find($conversationId);
            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversation not found'
                ], 404);
            }

            // Handle file upload
            $attachmentPath = null;
            $attachmentName = null;
            $messageType = 'text';

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $attachmentPath = $file->store('chat-attachments', 'public');
                $attachmentName = $file->getClientOriginalName();
                $messageType = 'image';
            }

            // Create message
            $message = Message::create([
                'conversation_id' => $conversationId,
                'sender_id' => $admin->user_id,
                'sender_type' => get_class($admin),
                'content' => $request->content ?: '',
                'attachment_path' => $attachmentPath,
                'attachment_name' => $attachmentName,
                'message_type' => $messageType,
            ]);

            // Update conversation last message time
            $conversation->update(['last_message_at' => now()]);

            // Increment unread count for other participants
            ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', '!=', $admin->user_id)
                ->increment('unread_count');

            // Broadcast message
            broadcast(new MessageSent($message, $admin));
            
            // Broadcast conversation update
            broadcast(new ConversationUpdated($conversation));

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => [
                    'id' => $message->id,
                    'content' => $message->content,
                    'message_type' => $message->message_type,
                    'attachment_path' => $message->attachment_path,
                    'attachment_name' => $message->attachment_name,
                    'created_at' => $message->created_at->toISOString(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send typing indicator (admin).
     */
    public function sendTyping(Request $request, $conversationId): JsonResponse
    {
        try {
            $admin = Auth::user();
            
            // Check if admin is participant
            $participant = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $admin->user_id)
                ->where('participant_type', get_class($admin))
                ->first();

            if (!$participant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $isTyping = $request->boolean('is_typing', true);

            // Broadcast typing indicator
            broadcast(new UserTyping($conversationId, $admin->user_id, get_class($admin), null, $isTyping));

            return response()->json([
                'success' => true,
                'message' => 'Typing indicator sent'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send typing indicator',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Close a conversation.
     */
    public function closeConversation($conversationId): JsonResponse
    {
        try {
            $admin = Auth::user();
            $conversation = Conversation::find($conversationId);
            
            if (!$conversation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversation not found'
                ], 404);
            }

            // Check if admin is participant
            $participant = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $admin->user_id)
                ->where('participant_type', get_class($admin))
                ->first();

            if (!$participant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            // Close conversation
            $conversation->update(['status' => 'closed']);

            // Broadcast conversation update
            broadcast(new ConversationUpdated($conversation));

            return response()->json([
                'success' => true,
                'message' => 'Conversation closed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to close conversation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the sender's name based on the sender type.
     */
    private function getSenderName($sender): string
    {
        if (!$sender) {
            return 'Unknown';
        }

        // Both User and Admin models have userDetails relationship
        if ($sender instanceof \App\Models\User || $sender instanceof \App\Models\Admin) {
            $userDetail = $sender->userDetails;
            if ($userDetail) {
                return trim($userDetail->first_name . ' ' . $userDetail->last_name) ?: $sender->email;
            }
            return $sender->email;
        }

        return 'Unknown';
    }

    /**
     * Get the customer's name based on the participant.
     */
    private function getCustomerName($customer): string
    {
        if (!$customer || !$customer->participant) {
            return 'Unknown';
        }

        $participant = $customer->participant;

        // Both User and Admin models have userDetails relationship
        if ($participant instanceof \App\Models\User || $participant instanceof \App\Models\Admin) {
            $userDetail = $participant->userDetails;
            if ($userDetail) {
                return trim($userDetail->first_name . ' ' . $userDetail->last_name) ?: $participant->email;
            }
            return $participant->email;
        }

        return 'Unknown';
    }
}