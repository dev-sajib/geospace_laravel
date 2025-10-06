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
        'user_id',
        'work_date',
        'day_of_week',
        'work_hours',
        'task_description',
        'status_id',
        'status_display_name',
        'submitted_at',
        'approved_at',
        'approved_by',
        'rejected_reason'
    ];

    protected $casts = [
        'work_date' => 'date',
        'work_hours' => 'decimal:2',
        'status_id' => 'integer',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'approved_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'contract_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    public function timesheetStatus()
    {
        return $this->belongsTo(TimesheetStatus::class, 'status_id', 'status_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'timesheet_id', 'timesheet_id');
    }
}
