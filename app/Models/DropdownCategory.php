<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropdownCategory extends Model
{
    use HasFactory;

    protected $table = 'dropdown_categories';
    protected $primaryKey = 'category_id';
    public $timestamps = true;
    const UPDATED_AT = null; // Only created_at timestamp

    protected $fillable = [
        'category_name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Relationships
    public function dropdownValues()
    {
        return $this->hasMany(DropdownValue::class, 'category_id', 'category_id');
    }
}
