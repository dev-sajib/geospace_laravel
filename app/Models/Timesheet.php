<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;

    protected $table = 'timesheets';
    protected $primaryKey = 'timesheet_id';
    public $timestamps = true;

    protected $fillable = [
        'contract_id',
        'freelancer_id',
        'company_id',
        'project_id',
        'start_date',
        'end_date',
        'status_id',
        'status_display_name',
        'total_hours',
        'hourly_rate',
        'total_amount',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
        'payment_requested_at',
        'payment_completed_at',
        'resubmission_count',
        'last_resubmitted_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_hours' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'status_id' => 'integer',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'payment_requested_at' => 'datetime',
        'payment_completed_at' => 'datetime',
        'resubmission_count' => 'integer',
        'last_resubmitted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the contract that owns the timesheet
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'contract_id');
    }

    /**
     * Get the freelancer that owns the timesheet
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id', 'user_id');
    }

    /**
     * Get the company that owns the timesheet
     */
    public function company()
    {
        return $this->belongsTo(CompanyDetail::class, 'company_id', 'company_id');
    }

    /**
     * Get the project that owns the timesheet
     */
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    /**
     * Get the reviewer of the timesheet
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'user_id');
    }

    /**
     * Get the status of the timesheet
     */
    public function status()
    {
        return $this->belongsTo(TimesheetStatus::class, 'status_id', 'status_id');
    }

    /**
     * Get the days for the timesheet (7 days)
     */
    public function days()
    {
        return $this->hasMany(TimesheetDay::class, 'timesheet_id', 'timesheet_id');
    }

    /**
     * Get the invoices for the timesheet
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'timesheet_id', 'timesheet_id');
    }

    /**
     * Get the payment requests for the timesheet
     */
    public function paymentRequests()
    {
        return $this->hasMany(PaymentRequest::class, 'timesheet_id', 'timesheet_id');
    }

    /**
     * Get the payments for the timesheet
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'timesheet_id', 'timesheet_id');
    }

    /**
     * Scope for pending timesheets
     */
    public function scopePending($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('status_name', 'Pending');
        });
    }

    /**
     * Scope for accepted timesheets
     */
    public function scopeAccepted($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('status_name', 'Accepted');
        });
    }

    /**
     * Scope for rejected timesheets
     */
    public function scopeRejected($query)
    {
        return $query->whereHas('status', function ($q) {
            $q->where('status_name', 'Rejected');
        });
    }

    /**
     * Scope for timesheets by freelancer
     */
    public function scopeByFreelancer($query, $freelancerId)
    {
        return $query->where('freelancer_id', $freelancerId);
    }

    /**
     * Scope for timesheets by company
     */
    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Check if timesheet is editable
     */
    public function isEditable()
    {
        return $this->status_display_name === 'Rejected';
    }

    /**
     * Check if payment can be requested
     */
    public function canRequestPayment()
    {
        return $this->status_display_name === 'Accepted' && !$this->payment_requested_at;
    }

    /**
     * Calculate total amount
     */
    public function calculateTotalAmount()
    {
        return $this->total_hours * $this->hourly_rate;
    }
}
