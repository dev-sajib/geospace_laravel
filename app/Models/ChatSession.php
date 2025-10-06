<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    use HasFactory;

    protected $table = 'chat_sessions';
    protected $primaryKey = 'session_id';
    public $timestamps = false; // Custom timestamp fields

    protected $fillable = [
        'user_id',
        'support_agent_id',
        'status',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function supportAgent()
    {
        return $this->belongsTo(User::class, 'support_agent_id', 'user_id');
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'session_id', 'session_id');
    }
}
