<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $table = 'payment_requests';
    protected $primaryKey = 'request_id';
    public $timestamps = true;

    protected $fillable = [
        'timesheet_id',
        'invoice_id',
        'freelancer_id',
        'requested_amount',
        'status',
        'requested_at',
        'processed_at',
        'processed_by',
        'payment_notes'
    ];

    protected $casts = [
        'requested_amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the timesheet that owns the payment request
     */
    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class, 'timesheet_id', 'timesheet_id');
    }

    /**
     * Get the invoice that owns the payment request
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    /**
     * Get the freelancer that owns the payment request
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id', 'user_id');
    }

    /**
     * Get the admin who processed the request
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by', 'user_id');
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope for completed requests
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted($processedBy)
    {
        $this->update([
            'status' => 'Completed',
            'processed_at' => now(),
            'processed_by' => $processedBy
        ]);
    }

    /**
     * Mark as rejected
     */
    public function markAsRejected($processedBy, $notes = null)
    {
        $this->update([
            'status' => 'Rejected',
            'processed_at' => now(),
            'processed_by' => $processedBy,
            'payment_notes' => $notes
        ]);
    }
}
