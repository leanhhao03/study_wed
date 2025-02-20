<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index($examId)
    {
        $exam = Exam::findOrFail($examId);

        $comments = $exam->comments()
                         ->with('user')
                         ->get();
    
        return response()->json($comments);
    }

    public function store(Request $request, $examId)
{
    $request->validate([
        'parent_id' => 'nullable|exists:comments,id',
        'comment' => 'required|string',
    ]);

    $comment = Comment::create([
        'user_id' => Auth::id(),
        'exam_id' => $examId,
        'parent_id' => $request->parent_id,
        'comment' => $request->comment,
    ]);

    return response()->json($comment, 201);
}

        
}
