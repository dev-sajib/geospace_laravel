<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    use HasFactory;

    protected $table = 'file_uploads';
    protected $primaryKey = 'file_id';
    public $timestamps = true;
    const UPDATED_AT = null; // Only created_at timestamp

    protected $fillable = [
        'user_id',
        'original_filename',
        'stored_filename',
        'file_path',
        'file_size',
        'mime_type',
        'file_category',
        'entity_type',
        'entity_id'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'entity_id' => 'integer',
        'created_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
