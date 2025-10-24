<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;
use App\Models\ConversationParticipant;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Conversation channel - only participants can access
Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);
    
    if (!$conversation) {
        return false;
    }
    
    // Check if user is a participant in this conversation
    $participant = ConversationParticipant::where('conversation_id', $conversationId)
        ->where('participant_id', $user->id)
        ->where('participant_type', get_class($user))
        ->first();
    
    return $participant !== null;
});

// Admin conversations channel - only admins can access
Broadcast::channel('admin.conversations', function ($user) {
    // Check if user is an admin (you may need to adjust this based on your admin model)
    return $user instanceof \App\Models\Admin || $user->hasRole('admin');
});

// Freelancer notifications channel - only the specific user can access
Broadcast::channel('user.{userId}.conversations', function ($user, $userId) {
    return (int) $user->user_id === (int) $userId;
});
