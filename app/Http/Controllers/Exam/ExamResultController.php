<?php

namespace App\Http\Controllers\Exam;

use App\Models\Exam;
use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamResultController extends Controller
{
    public function submit(Request $request, $examId)
    {
        $user = Auth::user();
        $exam = Exam::find($examId);

        if(!$exam){
            return response()->json(['message' => 'Exam not found'], 401);
        }

        $correcAnswers = json_decode($exam->question, true);
        $userAnswers = $request->input('answers');
        $score = 0;

        foreach($correcAnswers as $questionId => $correcAnswers)
        {
            if(isset($userAnswers[$questionId]) && $userAnswers[$questionId] === $correcAnswers){
                $score++;
            }
        }


        $result = ExamResult::create([
            'user_id' => $user->id,
            'exam_id' => $examId,
            'score' => $score,
            'user_answers' => json_encode($userAnswers)
            ]);

            return response() -> json([
                'message' => 'Exam submitted successfully',
                'result' => $result
            ]);
    }
}



