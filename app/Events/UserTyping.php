<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserTyping implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversationId;
    public $userId;
    public $userType;
    public $userName;
    public $isTyping;

    /**
     * Create a new event instance.
     */
    public function __construct($conversationId, $userId, $userType, $userName = null, $isTyping = true)
    {
        $this->conversationId = $conversationId;
        $this->userId = $userId;
        $this->userType = $userType;
        $this->userName = $userName ?: $this->getUserName($userId, $userType);
        $this->isTyping = $isTyping;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->conversationId),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'user_id' => $this->userId,
            'user_type' => $this->userType,
            'user_name' => $this->userName,
            'is_typing' => $this->isTyping,
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'user.typing';
    }

    /**
     * Get the user's name based on the user type and ID.
     */
    private function getUserName($userId, $userType): string
    {
        try {
            if ($userType === 'App\\Models\\User') {
                $user = \App\Models\User::with('userDetails')->find($userId);
                if ($user && $user->userDetails) {
                    return trim($user->userDetails->first_name . ' ' . $user->userDetails->last_name) ?: $user->email;
                }
                return $user ? $user->email : 'Unknown';
            }

            if ($userType === 'App\\Models\\Admin') {
                // Admin is a User with role_id = 1, so we need to temporarily disable the global scope
                $admin = \App\Models\Admin::withoutGlobalScope('adminOnly')
                    ->with('userDetails')
                    ->find($userId);
                if ($admin && $admin->userDetails) {
                    return trim($admin->userDetails->first_name . ' ' . $admin->userDetails->last_name) ?: $admin->email;
                }
                return $admin ? $admin->email : 'Unknown';
            }

            return 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
}