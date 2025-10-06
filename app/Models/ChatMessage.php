<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';
    protected $primaryKey = 'message_id';
    public $timestamps = true;
    const UPDATED_AT = null; // Only created_at timestamp

    protected $fillable = [
        'session_id',
        'sender_id',
        'message_text',
        'message_type',
        'attachment_url',
        'is_from_agent'
    ];

    protected $casts = [
        'is_from_agent' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Relationships
    public function chatSession()
    {
        return $this->belongsTo(ChatSession::class, 'session_id', 'session_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }
}
