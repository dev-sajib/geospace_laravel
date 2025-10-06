<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    use HasFactory;

    protected $table = 'company_details';
    protected $primaryKey = 'company_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_type',
        'industry',
        'company_size',
        'website',
        'description',
        'founded_year',
        'headquarters',
        'logo'
    ];

    protected $casts = [
        'founded_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'company_id', 'company_id');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'company_id', 'company_id');
    }
}
