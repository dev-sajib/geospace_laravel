<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';
    public $timestamps = true;

    protected $fillable = [
        'timesheet_id',
        'contract_id',
        'company_id',
        'freelancer_id',
        'invoice_number',
        'invoice_date',
        'total_hours',
        'hourly_rate',
        'subtotal',
        'tax_amount',
        'total_amount',
        'currency',
        'status',
        'due_date',
        'sent_at',
        'paid_at'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'total_hours' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'due_date' => 'date',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the timesheet that owns the invoice
     */
    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class, 'timesheet_id', 'timesheet_id');
    }

    /**
     * Get the contract that owns the invoice
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'contract_id');
    }

    /**
     * Get the company that owns the invoice
     */
    public function company()
    {
        return $this->belongsTo(CompanyDetail::class, 'company_id', 'company_id');
    }

    /**
     * Get the freelancer that owns the invoice
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id', 'user_id');
    }

    /**
     * Get the payment requests for the invoice
     */
    public function paymentRequests()
    {
        return $this->hasMany(PaymentRequest::class, 'invoice_id', 'invoice_id');
    }

    /**
     * Get the payments for the invoice
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'invoice_id');
    }

    /**
     * Scope for paid invoices
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'Paid');
    }

    /**
     * Scope for pending invoices
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', ['Generated', 'Sent']);
    }

    /**
     * Scope for overdue invoices
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'Paid')
            ->where('due_date', '<', now());
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue()
    {
        return $this->status !== 'Paid' && $this->due_date < now();
    }

    /**
     * Mark as paid
     */
    public function markAsPaid()
    {
        $this->update([
            'status' => 'Paid',
            'paid_at' => now()
        ]);
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::orderBy('invoice_id', 'desc')->first();
        $nextId = $lastInvoice ? $lastInvoice->invoice_id + 1 : 1;
        return 'INV-' . date('Ymd') . '-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }
}
