<?php

namespace App\Models;

/**
 * Admin model - represents administrative users
 * This is essentially an alias for the User model with admin privileges
 * In the database, admins are users with role_id = 1 (Admin)
 */
class Admin extends User
{
    /**
     * The table associated with the model.
     * Uses the same users table
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Boot the model and add global scope to filter admin users only
     */
    protected static function booted()
    {
        parent::booted();

        // Automatically filter to only return admin users (role_id = 1)
        static::addGlobalScope('adminOnly', function ($query) {
            $query->where('role_id', 1);
        });
    }

    /**
     * Get admin's name
     * Tries to get from userDetails, falls back to email
     */
    public function getNameAttribute(): string
    {
        if ($this->userDetails) {
            $fullName = trim($this->userDetails->first_name . ' ' . $this->userDetails->last_name);
            if ($fullName) {
                return $fullName;
            }
        }
        return $this->email;
    }

    /**
     * Get admin's username (alias for name)
     */
    public function getUserNameAttribute(): string
    {
        return $this->name;
    }
}
