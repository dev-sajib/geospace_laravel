<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ConversationParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'participant_id',
        'participant_type',
        'role',
        'last_read_at',
        'unread_count',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
    ];

    /**
     * Get the conversation that owns the participant.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the participant (User, Admin, etc.).
     */
    public function participant(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to get customer participants.
     */
    public function scopeCustomers($query)
    {
        return $query->where('role', 'customer');
    }

    /**
     * Scope to get support agent participants.
     */
    public function scopeSupportAgents($query)
    {
        return $query->where('role', 'support_agent');
    }

    /**
     * Mark messages as read.
     */
    public function markAsRead()
    {
        $this->update([
            'last_read_at' => now(),
            'unread_count' => 0,
        ]);
    }

    /**
     * Increment unread count.
     */
    public function incrementUnreadCount()
    {
        $this->increment('unread_count');
    }
}