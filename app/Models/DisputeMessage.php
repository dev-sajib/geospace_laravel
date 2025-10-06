<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisputeMessage extends Model
{
    use HasFactory;

    protected $table = 'dispute_messages';
    protected $primaryKey = 'message_id';
    public $timestamps = true;
    const UPDATED_AT = null; // Only created_at timestamp

    protected $fillable = [
        'ticket_id',
        'sender_id',
        'message_text',
        'attachment_url',
        'is_internal'
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Relationships
    public function disputeTicket()
    {
        return $this->belongsTo(DisputeTicket::class, 'ticket_id', 'ticket_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }
}
