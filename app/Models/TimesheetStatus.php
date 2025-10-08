<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesheetStatus extends Model
{
    use HasFactory;

    protected $table = 'timesheet_status';
    protected $primaryKey = 'status_id';
    public $timestamps = false; // Only has created_at

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'status_name',
        'status_description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'Pending';
    const STATUS_ACCEPTED = 'Accepted';
    const STATUS_REJECTED = 'Rejected';

    /**
     * Get the timesheets for this status
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class, 'status_id', 'status_id');
    }

    /**
     * Scope for active statuses
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get status by name
     */
    public static function getByName($name)
    {
        return self::where('status_name', $name)->first();
    }

    /**
     * Get pending status
     */
    public static function getPendingStatus()
    {
        return self::where('status_name', self::STATUS_PENDING)->first();
    }

    /**
     * Get accepted status
     */
    public static function getAcceptedStatus()
    {
        return self::where('status_name', self::STATUS_ACCEPTED)->first();
    }

    /**
     * Get rejected status
     */
    public static function getRejectedStatus()
    {
        return self::where('status_name', self::STATUS_REJECTED)->first();
    }
}
