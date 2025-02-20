<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject',
        'questions',
        'duration'
    ];

    protected $casts = [
        'questions' => 'array'
    ];

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->with('replies');
    }
}
