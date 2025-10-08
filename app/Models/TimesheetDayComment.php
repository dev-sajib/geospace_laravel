<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesheetDayComment extends Model
{
    use HasFactory;

    protected $table = 'timesheet_day_comments';
    protected $primaryKey = 'comment_id';
    public $timestamps = false; // Only has created_at

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'day_id',
        'timesheet_id',
        'comment_by',
        'comment_type',
        'comment_text'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    /**
     * Get the day that owns the comment
     */
    public function day()
    {
        return $this->belongsTo(TimesheetDay::class, 'day_id', 'day_id');
    }

    /**
     * Get the timesheet that owns the comment
     */
    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class, 'timesheet_id', 'timesheet_id');
    }

    /**
     * Get the user who created the comment
     */
    public function commenter()
    {
        return $this->belongsTo(User::class, 'comment_by', 'user_id');
    }

    /**
     * Scope for company comments
     */
    public function scopeCompanyComments($query)
    {
        return $query->where('comment_type', 'Company');
    }

    /**
     * Scope for freelancer comments
     */
    public function scopeFreelancerComments($query)
    {
        return $query->where('comment_type', 'Freelancer');
    }
}
