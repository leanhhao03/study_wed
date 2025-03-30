<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;

class ExamController extends Controller
{
    // Lấy danh sách tất cả bài thi
    public function index(Request $request)
    {
        $query = Exam::select('id', 'title', 'subject', 'duration','questions','created_at');
        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'LIKE', "%$search%");
        }
        if ($request->has('subject')) {
            $subjects = $request->input('subject');
            $query->where('subject', $subjects);
        }

        $exams = $query->get();

        $examsWithCounts = $exams->map(function ($exam) {
            $questions = json_decode($exam->questions, true);
            return [
                'id' => $exam->id,
                'title' => $exam->title,
                'subject' => $exam->subject,
                'duration' => $exam->duration,
                'total_questions' => is_array($questions) ? count($questions) : 0,
                'created_at' => $exam->created_at->format('d/m/Y')
            ];
        });
        
        return response()->json($examsWithCounts);
    }    
    
    // Xem chi tiết một bài thi
    public function show($id)
    {
        $exam = Exam::find($id);
        if (!$exam) {
            return response()->json(['message' => 'Không tìm thấy bài thi'], 404);
        }
        
        // Trả về danh sách câu hỏi và đáp án
        return response()->json([
            'id' => $exam->id,
            'title' => $exam->title,
            'duration' => $exam->duration,
            'subject' => $exam->subject,
            'questions' => json_decode($exam->questions), 
        ]);
    }
    // Bắt đầu bài thi (lưu thời gian bắt đầu)
    public function startExam(Request $request, $id)
    {

        $exam = Exam::find($id);
        if (!$exam) {
            return response()->json(['message' => 'Không tìm thấy bài thi'], 404);
        }

        //Kiểm tra xem user đã bắt đầu bài thi này chưa
        $user = Auth::id(); 
        $existingExamResult = ExamResult::where('exam_id', $id)
            ->where('user_id', $user)
            ->first();

        if ($existingExamResult) {
            return response()->json([
                'message' => 'Bạn đã bắt đầu bài thi này trước đó!',
                'start_time' => $existingExamResult->start_time,
            ]);
        }

        // Tạo mới kết quả làm bài & lưu thời gian bắt đầu
        $examResult = ExamResult::create([
            'exam_id' => $id,
            'user_id' => $user,
            'start_time' => Carbon::now(),
            'score' => null,
            'user_answers' => json_encode([])
        ]);

        return response()->json([
            'message' => 'Bài thi đã bắt đầu!',
            'start_time' => $examResult->start_time,
            'exam' => $exam
        ]);
    }

    // Tạo bài thi mới
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'questions' => 'required|array',
            'subject' => 'required|string',
            'duration' => 'nullable|integer',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array',
            'questions.*.correct_answer' => 'required|string'
        ]);

        $exam = Exam::create([
            'title'  => $request->title,
            'subject' => $request->subject,
            'duration' => $request->duration,
            'questions' => json_encode($request->questions)
        ]);

        return response()->json(['message' => 'Bài thi đã được tạo thành công!', 'exam' => $exam], 201);
    }


    private function parseDocxSimple($file)
    {
        $phpWord = IOFactory::load($file->getRealPath());
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
    
        ob_start();
        $htmlWriter->save('php://output');
        $htmlContent = ob_get_clean();
    
        // Chuyển thẻ <br> thành xuống dòng để giữ định dạng
        $textContent = preg_replace('/<br\s*\/?>/i', "\n", $htmlContent);
        $textContent = strip_tags($textContent); // Loại bỏ tất cả thẻ HTML
    
        return $this->extractQuestions($textContent);
    }
    
    /**
     * Tách câu hỏi từ nội dung văn bản đơn giản
     */
    private function extractQuestions($text)
{
    $lines = explode("\n", $text);
    $questions = [];
    $currentQuestion = null;

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;

        // Nếu bắt đầu bằng "Câu X." -> Đây là một câu hỏi mới
        if (preg_match('/^Câu\s*\d+\./', $line)) {
            if ($currentQuestion) {
                $questions[] = $currentQuestion;
            }
            $currentQuestion = ['question' => $line, 'options' => [], 'correct_answer' => ''];
        }
        // Nếu là đáp án A, B, C, D
        elseif (preg_match('/^[A-D]\./', $line)) {
            if ($currentQuestion) {
                $currentQuestion['options'][] = $line;
            }
        }
        // Nếu là "Đáp án đúng: X"
        elseif (preg_match('/Đáp án đúng:\s*([A-D])/', $line, $matches)) {
            if ($currentQuestion) {
                $currentQuestion['correct_answer'] = $matches[1];
            }
        }
    }

    if ($currentQuestion) {
        $questions[] = $currentQuestion;
    }

    return $questions;
}
public function createFromDocx(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'subject' => 'required|string',
        'duration' => 'nullable|integer',
        'file' => 'required|file|mimes:docx|max:2048'
    ]);

    $questions = $this->parseDocxSimple($request->file('file'));

    if (empty($questions)) {
        return response()->json(['error' => 'Không tìm thấy câu hỏi hợp lệ trong file!'], 400);
    }

    $totalquestion =  count($questions);

    // Lưu vào database
    $exam = Exam::create([
        'title' => $request->title,
        'subject' => $request->subject,
        'duration' => $request->duration,
        'questions' => json_encode($questions)
    ]);

    return response()->json([
        'message' => 'Bài thi đã được tạo thành công!',
        'total_question' => $totalquestion,
        'exam' => $exam
    ], 201);
}
}
