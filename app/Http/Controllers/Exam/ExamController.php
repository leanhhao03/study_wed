<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {        
        return Exam::all();
    }

    public function startExam($id)
    {
        if(!Auth::check())
        {
            return response()->json(['message' => 'You must be logged in to start the exam'],401);
        }

        $exam = Exam::find($id);
        if(!$exam){
            return response()->json(['message' => 'Exam not found'], 401);
    }

    return response() ->json($exam);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'questions' => 'required|array',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array',
            'questions.*.correct_answer' => 'required|string'
        ]);

        $exam = Exam::create([
            'title'  => $request->title,
            'questions' => $request->questions
        ]);

        return response()-> json(['message' => 'Exam created successfully', 'exam' =>$exam], 201);
    }

    public function show($id)
    {
        $exam = Exam::find($id);
        if(!$exam){
            return response()->json(['message' => 'Exam not found'], 401);
            }
            return response()->json($exam);
    }
}
