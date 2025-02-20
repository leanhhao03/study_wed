<?php

namespace App\Http\Controllers\Exam;

use App\Models\Exam;
use App\Http\Controllers\Controller;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExamResultController extends Controller
{
    // Nộp bài thi & tính điểm
    public function submit(Request $request, $examId)
    {
        $user = Auth::user();
        $exam = Exam::find($examId);

        if (!$exam) {
            return response()->json(['message' => 'Không tìm thấy bài thi'], 404);
        }

        $examResult = ExamResult::where('exam_id', $examId)
            ->where('user_id', $user->id)
            ->first();

        if (!$examResult) {
            return response()->json(['message' => 'Bạn chưa bắt đầu bài thi này!'], 400);
        }

        // Kiểm tra nếu đã hết thời gian
        $startTime = Carbon::parse($examResult->start_time);
        $currentTime = Carbon::now();
        $examDuration = 30; // Giả sử mỗi bài thi có thời gian 30 phút

        if ($currentTime->diffInMinutes($startTime) >= $examDuration) {
            return response()->json(['message' => 'Thời gian làm bài đã hết!'], 403);
        }

        // Tính điểm
        $correctAnswers = collect(json_decode($exam->questions, true))->pluck('correct_answer');
        $userAnswers = $request->input('answers');
        $score = 0;

        foreach ($correctAnswers as $index => $correctAnswer) {
            if (isset($userAnswers[$index]) && $userAnswers[$index] == $correctAnswer) {
                $score++;
            }
        }

        // Cập nhật kết quả
        $examResult->update([
            'score' => $score,
            'user_answers' => json_encode($userAnswers)
        ]);

        return response()->json([
            'message' => 'Bài thi đã được nộp!',
            'score' => $score
        ]);
    }
}
