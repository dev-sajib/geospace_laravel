<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisputeTicket extends Model
{
    use HasFactory;

    protected $table = 'dispute_tickets';
    protected $primaryKey = 'ticket_id';
    public $timestamps = true;

    protected $fillable = [
        'contract_id',
        'created_by',
        'assigned_agent_id',
        'subject',
        'description',
        'status_id',
        'priority',
        'category',
        'resolution_notes',
        'resolved_at',
        'resolved_by'
    ];

    protected $casts = [
        'status_id' => 'integer',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'contract_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_agent_id', 'user_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by', 'user_id');
    }

    public function disputeMessages()
    {
        return $this->hasMany(DisputeMessage::class, 'ticket_id', 'ticket_id');
    }
}
