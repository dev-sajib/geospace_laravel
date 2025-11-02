<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation;

    /**
     * Create a new event instance.
     */
    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.conversations'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $customer = $this->conversation->customer;
        $latestMessage = $this->conversation->latestMessage()->first();
        
        return [
            'id' => $this->conversation->id,
            'subject' => $this->conversation->subject,
            'status' => $this->conversation->status,
            'last_message_at' => $this->conversation->last_message_at?->toISOString(),
            'customer_name' => $this->getCustomerName($customer),
            'latest_message_preview' => $latestMessage?->content ? substr($latestMessage->content, 0, 100) . '...' : null,
            'unread_count' => $this->conversation->participants()->where('role', 'support_agent')->sum('unread_count'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'conversation.updated';
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

        // If it's a User model, get name from role-specific details
        if ($participant instanceof \App\Models\User) {
            return $participant->full_name;
        }

        // If it's an Admin model, try to get name or email
        if (method_exists($participant, 'name')) {
            return $participant->name;
        }
        
        if (method_exists($participant, 'UserName')) {
            return $participant->UserName;
        }
        
        if (method_exists($participant, 'email')) {
            return $participant->email;
        }

        return 'Unknown';
    }
}