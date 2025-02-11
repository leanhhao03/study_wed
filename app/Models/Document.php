<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $table = 'document';

    protected $fillable = [
        'dcm_title', 
        'dcm_description', 
        'dcm_file_path', 
        'dcm_file_mime', 
        'us_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'us_id');
    }

    // Accessor: Lấy URL đầy đủ của file
    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->dcm_file_path);
    }
}
