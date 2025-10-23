<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use App\Events\MessageSent;
use App\Events\UserTyping;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Create a new conversation (chat request).
     */
    public function createConversation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'purpose' => 'nullable|string|max:1000',
            'attachment' => 'nullable|image|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            // Handle file upload
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('chat-attachments', 'public');
            }

            // Create conversation
            $conversation = Conversation::create([
                'subject' => $request->subject,
                'purpose' => $request->purpose,
                'attachment_path' => $attachmentPath,
                'status' => 'open',
            ]);

            // Add user as participant
            ConversationParticipant::create([
                'conversation_id' => $conversation->id,
                'participant_id' => $user->user_id,
                'participant_type' => get_class($user),
                'role' => 'customer',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Chat request created successfully',
                'conversation' => [
                    'id' => $conversation->id,
                    'subject' => $conversation->subject,
                    'purpose' => $conversation->purpose,
                    'attachment_path' => $conversation->attachment_path,
                    'status' => $conversation->status,
                    'created_at' => $conversation->created_at->toISOString(),
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create chat request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's conversations.
     */
    public function getConversations(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $conversations = Conversation::whereHas('participants', function ($query) use ($user) {
                $query->where('participant_id', $user->user_id)
                      ->where('participant_type', get_class($user));
            })
            ->with(['participants', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

            $conversationsData = $conversations->map(function ($conversation) use ($user) {
                $participant = $conversation->participants()
                    ->where('participant_id', $user->user_id)
                    ->where('participant_type', get_class($user))
                    ->first();

                $latestMessage = $conversation->latestMessage()->first();
                
                return [
                    'id' => $conversation->id,
                    'subject' => $conversation->subject,
                    'purpose' => $conversation->purpose,
                    'attachment_path' => $conversation->attachment_path,
                    'status' => $conversation->status,
                    'unread_count' => $participant->unread_count ?? 0,
                    'last_message_at' => $conversation->last_message_at?->toISOString(),
                    'latest_message_preview' => $latestMessage?->content ? substr($latestMessage->content, 0, 100) . '...' : null,
                    'created_at' => $conversation->created_at->toISOString(),
                ];
            });

            return response()->json([
                'success' => true,
                'conversations' => $conversationsData
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
     * Get messages for a conversation.
     */
    public function getMessages(Request $request, $conversationId): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Check if user is participant
            $participant = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $user->user_id)
                ->where('participant_type', get_class($user))
                ->first();

            if (!$participant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $messages = Message::where('conversation_id', $conversationId)
                ->with('sender')
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark messages as read
            $participant->markAsRead();

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

            return response()->json([
                'success' => true,
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
     * Send a message.
     */
    public function sendMessage(Request $request, $conversationId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:2000',
            'attachment' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            
            // Check if user is participant
            $participant = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $user->user_id)
                ->where('participant_type', get_class($user))
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
                $messageType = 'image'; // Assuming only images for now
            }

            // Create message
            $message = Message::create([
                'conversation_id' => $conversationId,
                'sender_id' => $user->user_id,
                'sender_type' => get_class($user),
                'content' => $request->content,
                'attachment_path' => $attachmentPath,
                'attachment_name' => $attachmentName,
                'message_type' => $messageType,
            ]);

            // Update conversation last message time
            $conversation->update(['last_message_at' => now()]);

            // Increment unread count for other participants
            ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', '!=', $user->user_id)
                ->increment('unread_count');

            // Broadcast message
            broadcast(new MessageSent($message));

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
     * Send typing indicator.
     */
    public function sendTyping(Request $request, $conversationId): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Check if user is participant
            $participant = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $user->user_id)
                ->where('participant_type', get_class($user))
                ->first();

            if (!$participant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            $isTyping = $request->boolean('is_typing', true);

            // Broadcast typing indicator
            broadcast(new UserTyping($conversationId, $user->user_id, get_class($user), null, $isTyping));

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
     * Delete a conversation.
     */
    public function deleteConversation($conversationId): JsonResponse
    {
        try {
            $user = Auth::user();
            
            // Check if user is participant
            $participant = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $user->user_id)
                ->where('participant_type', get_class($user))
                ->first();

            if (!$participant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied'
                ], 403);
            }

            // Remove user from conversation (soft delete by removing participant)
            $participant->delete();

            // If no participants left, delete the conversation
            $remainingParticipants = ConversationParticipant::where('conversation_id', $conversationId)->count();
            if ($remainingParticipants === 0) {
                $conversation = Conversation::find($conversationId);
                if ($conversation) {
                    // Delete attachment file if exists
                    if ($conversation->attachment_path) {
                        Storage::disk('public')->delete($conversation->attachment_path);
                    }
                    $conversation->delete();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Conversation deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete conversation',
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

        // If it's a User model, get name from UserDetail
        if ($sender instanceof \App\Models\User) {
            $userDetail = $sender->userDetails;
            if ($userDetail) {
                return trim($userDetail->first_name . ' ' . $userDetail->last_name) ?: $sender->email;
            }
            return $sender->email;
        }

        // If it's an Admin model, try to get name or email
        if (method_exists($sender, 'name')) {
            return $sender->name;
        }
        
        if (method_exists($sender, 'UserName')) {
            return $sender->UserName;
        }
        
        if (method_exists($sender, 'email')) {
            return $sender->email;
        }

        return 'Unknown';
    }
}