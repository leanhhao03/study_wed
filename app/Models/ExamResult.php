<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = ['exam_id', 'user_id', 'user_answers', 'score', 'start_time'];

    protected $casts = [
        'user_answers' => 'array',
        'start_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Exam::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
