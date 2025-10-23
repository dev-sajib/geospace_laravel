<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'purpose',
        'attachment_path',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Get the participants for the conversation.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    /**
     * Get the messages for the conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the latest message for the conversation.
     */
    public function latestMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    /**
     * Get users participating in this conversation.
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'participant', 'conversation_participants');
    }

    /**
     * Get admins participating in this conversation.
     */
    public function admins(): MorphToMany
    {
        return $this->morphedByMany(Admin::class, 'participant', 'conversation_participants');
    }

    /**
     * Scope to get open conversations.
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope to get closed conversations.
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Get the customer participant.
     */
    public function customer()
    {
        return $this->participants()->where('role', 'customer')->first();
    }

    /**
     * Get the support agent participant.
     */
    public function supportAgent()
    {
        return $this->participants()->where('role', 'support_agent')->first();
    }
}