<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    protected $primaryKey = 'project_id';
    public $timestamps = true;

    protected $fillable = [
        'company_id',
        'project_title',
        'project_description',
        'project_type',
        'budget_min',
        'budget_max',
        'currency',
        'duration_weeks',
        'status',
        'skills_required',
        'location',
        'is_remote',
        'deadline'
    ];

    protected $casts = [
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'duration_weeks' => 'integer',
        'skills_required' => 'array',
        'is_remote' => 'boolean',
        'deadline' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(CompanyDetail::class, 'company_id', 'company_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'project_id', 'project_id');
    }
}
