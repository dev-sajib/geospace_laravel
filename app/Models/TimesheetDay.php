<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesheetDay extends Model
{
    use HasFactory;

    protected $table = 'timesheet_days';
    protected $primaryKey = 'day_id';
    public $timestamps = true;

    protected $fillable = [
        'timesheet_id',
        'work_date',
        'day_name',
        'day_number',
        'hours_worked',
        'task_description'
    ];

    protected $casts = [
        'work_date' => 'date',
        'day_number' => 'integer',
        'hours_worked' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the timesheet that owns the day
     */
    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class, 'timesheet_id', 'timesheet_id');
    }

    /**
     * Get the comments for the day
     */
    public function comments()
    {
        return $this->hasMany(TimesheetDayComment::class, 'day_id', 'day_id');
    }

    /**
     * Get company comments
     */
    public function companyComments()
    {
        return $this->hasMany(TimesheetDayComment::class, 'day_id', 'day_id')
            ->where('comment_type', 'Company');
    }

    /**
     * Get freelancer comments
     */
    public function freelancerComments()
    {
        return $this->hasMany(TimesheetDayComment::class, 'day_id', 'day_id')
            ->where('comment_type', 'Freelancer');
    }

    /**
     * Check if day has company comments
     */
    public function hasCompanyComments()
    {
        return $this->comments()->where('comment_type', 'Company')->exists();
    }
}
