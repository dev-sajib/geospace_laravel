<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropdownValue extends Model
{
    use HasFactory;

    protected $table = 'dropdown_values';
    protected $primaryKey = 'value_id';
    public $timestamps = true;
    const UPDATED_AT = null; // Only created_at timestamp

    protected $fillable = [
        'category_id',
        'display_name',
        'value',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(DropdownCategory::class, 'category_id', 'category_id');
    }
}
