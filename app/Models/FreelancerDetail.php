<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerDetail extends Model
{
    use HasFactory;

    protected $table = 'freelancer_details';
    protected $primaryKey = 'freelancer_detail_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'designation',
        'experience_years',
        'profile_image',
        'bio',
        'summary',
        'linkedin_url',
        'website_url',
        'resume_or_cv',
        'hourly_rate',
        'availability_status'
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'experience_years' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
