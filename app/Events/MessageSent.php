<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $conversationId;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->conversationId = $message->conversation_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('conversation.' . $this->conversationId),
        ];

        // Also broadcast to the recipient's notification channel
        $conversation = $this->message->conversation;
        if ($conversation) {
            $participants = $conversation->participants;
            foreach ($participants as $participant) {
                if ($participant->participant_id !== $this->message->sender_id) {
                    $channels[] = new PrivateChannel('user.'.$participant->participant_id.'.conversations');
                }
            }
        }

        return $channels;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
            'sender_type' => $this->message->sender_type,
            'sender_name' => $this->getSenderName(),
            'content' => $this->message->content,
            'message_type' => $this->message->message_type,
            'attachment_path' => $this->message->attachment_path,
            'attachment_name' => $this->message->attachment_name,
            'is_read' => $this->message->is_read,
            'created_at' => $this->message->created_at->toISOString(),
            'formatted_time' => $this->message->formatted_time,
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the sender's name based on the sender type.
     */
    private function getSenderName(): string
    {
        $sender = $this->message->sender;
        
        if (!$sender) {
            return 'Unknown';
        }

        // If it's a User model, get name from role-specific details
        if ($sender instanceof \App\Models\User) {
            return $sender->full_name;
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