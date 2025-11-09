<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'email',
        'password_hash',
        'role_id',
        'auth_provider',
        'is_active',
        'is_verified',
        'verification_status',
        'email_verified_at',
        'last_login'
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // JWT Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'role_id' => $this->role_id,
            'email' => $this->email
        ];
    }

    // Override password field for authentication
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function companyDetails()
    {
        return $this->hasOne(CompanyDetail::class, 'user_id', 'user_id');
    }

    public function freelancerDetails()
    {
        return $this->hasOne(FreelancerDetail::class, 'user_id', 'user_id');
    }

    public function adminDetails()
    {
        return $this->hasOne(AdminDetail::class, 'user_id', 'user_id');
    }

    public function supportDetails()
    {
        return $this->hasOne(SupportDetail::class, 'user_id', 'user_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'freelancer_id', 'user_id');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class, 'user_id', 'user_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    public function disputeTickets()
    {
        return $this->hasMany(DisputeTicket::class, 'created_by', 'user_id');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id', 'user_id');
    }

    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class, 'user_id', 'user_id');
    }

    public function chatSessions()
    {
        return $this->hasMany(ChatSession::class, 'user_id', 'user_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'user_id');
    }

    public function visitorLogs()
    {
        return $this->hasMany(VisitorLog::class, 'user_id', 'user_id');
    }

    /**
     * User expertise relationship
     */
    public function expertise()
    {
        return $this->hasMany(\App\Models\Expertise::class, 'user_id', 'user_id');
    }

    /**
     * User skills relationship
     */
    public function skills()
    {
        return $this->hasMany(\App\Models\Skill::class, 'user_id', 'user_id');
    }

    /**
     * Helper methods to get personal data from role-specific tables
     */

    /**
     * Get user's first name from appropriate role-specific table
     */
    public function getFirstNameAttribute($value)
    {
        switch ($this->role_id) {
            case 1: // Admin
                return $this->adminDetails->first_name ?? null;
            case 2: // Freelancer
                return $this->freelancerDetails->first_name ?? null;
            case 3: // Company
                return $this->companyDetails->contact_first_name ?? null;
            case 4: // Support
                return $this->supportDetails->first_name ?? null;
            default:
                return null;
        }
    }

    /**
     * Get user's last name from appropriate role-specific table
     */
    public function getLastNameAttribute($value)
    {
        switch ($this->role_id) {
            case 1: // Admin
                return $this->adminDetails->last_name ?? null;
            case 2: // Freelancer
                return $this->freelancerDetails->last_name ?? null;
            case 3: // Company
                return $this->companyDetails->contact_last_name ?? null;
            case 4: // Support
                return $this->supportDetails->last_name ?? null;
            default:
                return null;
        }
    }

    /**
     * Get user's phone from appropriate role-specific table
     */
    public function getPhoneAttribute($value)
    {
        switch ($this->role_id) {
            case 1: // Admin
                return $this->adminDetails->phone ?? null;
            case 2: // Freelancer
                return $this->freelancerDetails->phone ?? null;
            case 3: // Company
                return $this->companyDetails->contact_phone ?? null;
            case 4: // Support
                return $this->supportDetails->phone ?? null;
            default:
                return null;
        }
    }

    /**
     * Get user's full name
     */
    public function getFullNameAttribute(): string
    {
        $firstName = $this->first_name;
        $lastName = $this->last_name;

        $fullName = trim(($firstName ?? '') . ' ' . ($lastName ?? ''));
        return $fullName ?: $this->email;
    }

    /**
     * Get user's position/designation based on role
     */
    public function getPositionAttribute(): ?string
    {
        switch ($this->role_id) {
            case 1: // Admin
                return 'Administrator';
            case 2: // Freelancer
                return $this->freelancerDetails->designation ?? null;
            case 3: // Company
                return $this->companyDetails->contact_designation ?? null;
            case 4: // Support
                return 'Support Agent';
            default:
                return 'User';
        }
    }

}
