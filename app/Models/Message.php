<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'sender_type',
        'content',
        'attachment_path',
        'attachment_name',
        'message_type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the conversation that owns the message.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the sender (User, Admin, etc.).
     */
    public function sender(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to get unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope to get read messages.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope to get text messages.
     */
    public function scopeText($query)
    {
        return $query->where('message_type', 'text');
    }

    /**
     * Scope to get image messages.
     */
    public function scopeImage($query)
    {
        return $query->where('message_type', 'image');
    }

    /**
     * Scope to get file messages.
     */
    public function scopeFile($query)
    {
        return $query->where('message_type', 'file');
    }

    /**
     * Mark message as read.
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Get the formatted time for display.
     */
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }

    /**
     * Get the formatted date for display.
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y');
    }
}