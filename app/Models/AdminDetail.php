<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminDetail extends Model
{
    use HasFactory;

    protected $table = 'admin_details';
    protected $primaryKey = 'admin_detail_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'profile_image'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
