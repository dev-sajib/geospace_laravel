<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoSupportRequest extends Model
{
    protected $table = 'video_support_requests';
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'freelancer_id',
        'company_id',
        'meeting_date',
        'meeting_time',
        'video_link',
        'status',
        'notes',
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: Video support request belongs to a freelancer (user)
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id', 'user_id');
    }

    /**
     * Relationship: Video support request belongs to a company (user)
     */
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id', 'user_id');
    }

    /**
     * Get the user (freelancer or company) for this request
     */
    public function user()
    {
        return $this->freelancer_id
            ? $this->freelancer()
            : $this->company();
    }

    /**
     * Scope: Get open requests
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'Open');
    }

    /**
     * Scope: Get scheduled requests
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'Scheduled');
    }

    /**
     * Scope: Get completed requests
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    /**
     * Check if request has video link
     */
    public function hasVideoLink()
    {
        return !empty($this->video_link);
    }
}
