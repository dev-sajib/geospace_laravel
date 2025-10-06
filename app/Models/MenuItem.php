<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'menu_items';
    protected $primaryKey = 'menu_id';
    public $timestamps = true;
    const UPDATED_AT = null; // Only created_at timestamp

    protected $fillable = [
        'parent_menu_id',
        'menu_name',
        'menu_url',
        'menu_icon',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_menu_id', 'menu_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_menu_id', 'menu_id');
    }

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'menu_id', 'menu_id');
    }
}
