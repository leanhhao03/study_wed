<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'user_id',
        ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
