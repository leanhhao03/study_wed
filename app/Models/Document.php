<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';
    protected $primaryKey = 'Dcm_id';

    protected $fillable = [
        'dcm_title', 
        'dcm_description',
        'subject', 
        'dcm_file_path', 
        'dcm_file_mime', 
        'dcm_preview_path',
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
