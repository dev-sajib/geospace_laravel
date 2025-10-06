<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contracts';
    protected $primaryKey = 'contract_id';
    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'freelancer_id',
        'company_id',
        'contract_title',
        'contract_description',
        'hourly_rate',
        'total_amount',
        'start_date',
        'end_date',
        'status',
        'payment_terms',
        'milestones'
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'milestones' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id', 'user_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyDetail::class, 'company_id', 'company_id');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class, 'contract_id', 'contract_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'contract_id', 'contract_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'contract_id', 'contract_id');
    }

    public function disputeTickets()
    {
        return $this->hasMany(DisputeTicket::class, 'contract_id', 'contract_id');
    }
}
