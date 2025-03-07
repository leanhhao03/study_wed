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
        // Lấy user_id từ request
        $user = $request->input('user_id'); 
        if (!$user) {
            return response()->json(['message' => 'Thiếu thông tin người dùng!'], 400);
        }
    
        // Tìm bài thi
        $exam = Exam::find($examId);
        if (!$exam) {
            return response()->json(['message' => 'Không tìm thấy bài thi'], 404);
        }
    
        // Tìm kết quả bài thi của user
        $examResult = ExamResult::where('exam_id', $examId)
            ->where('user_id', $user)
            ->first();
    
        if (!$examResult) {
            return response()->json(['message' => 'Bạn chưa bắt đầu bài thi này!'], 400);
        }
    
        // Kiểm tra nếu đã hết thời gian
        $startTime = Carbon::parse($examResult->start_time);
        $currentTime = Carbon::now();
        $examDuration = $exam->duration ?? 30; // Lấy thời gian từ exam (mặc định 30 phút)
    
        if ($currentTime->diffInMinutes($startTime) >= $examDuration) {
            return response()->json(['message' => 'Thời gian làm bài đã hết!'], 403);
        }
    
        // Kiểm tra xem câu trả lời có hợp lệ không
        $userAnswers = $request->input('answers');
        if (!is_array($userAnswers)) {
            return response()->json(['message' => 'Dữ liệu câu trả lời không hợp lệ!'], 400);
        }
    
        // Tính điểm
        $correctAnswers = collect(json_decode($exam->questions, true))->pluck('correct_answer');
        $score = 0;
    
        foreach ($correctAnswers as $index => $correctAnswer) {
            if (isset($userAnswers[$index]) && $userAnswers[$index] == $correctAnswer) {
                $score++;
            }
        }
    
        // Cập nhật kết quả
        $examResult->update([
            'score' => $score,
            'user_answers' => json_encode($userAnswers),
        ]);
    
        return response()->json([
            'message' => 'Bài thi đã được nộp!',
            'score' => $score,
        ]);
    }
    
}
