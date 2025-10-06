<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesheetStatus extends Model
{
    use HasFactory;

    protected $table = 'timesheet_status';
    protected $primaryKey = 'status_id';
    public $timestamps = false;

    protected $fillable = [
        'status_name',
        'status_description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class, 'status_id', 'status_id');
    }
}
