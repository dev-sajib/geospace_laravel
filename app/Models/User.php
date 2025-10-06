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
        'user_position',
        'auth_provider',
        'is_active',
        'is_verified',
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

    public function userDetails()
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'user_id');
    }

    public function companyDetails()
    {
        return $this->hasOne(CompanyDetail::class, 'user_id', 'user_id');
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
}