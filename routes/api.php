<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Document\FileController;
use App\Http\Controllers\Auth\ModifyAuthenticate;
use App\Http\Controllers\Document\CommentController;
use App\Http\Controllers\Document\FavoriteDocument;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\Exam\ExamResultController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\Verify\ResetPasswordController;
use App\Models\Document;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);
    Route::post('edit/{id}', [ModifyAuthenticate::class, 'updateProfile']);
    Route::get('/user', [AuthenticatedSessionController::class, 'getUser'])->middleware('auth');
    Route::get('/fecthUser', [AuthenticatedSessionController::class, 'getNonZeroRoleUsers']);
});

//Mail Controller
Route::post('/send-email', [MailController::class, 'sendEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

//file controller
Route::prefix('documents')->group(function () {
    // Upload và chuyển đổi file
    Route::post('/upload', [FileController::class, 'uploadAndConvert']);
    // Lấy danh sách tất cả các file đã upload
    Route::get('/all', [FileController::class, 'getFile']);
    //get preview
    Route::get('/preview', [FileController::class, 'getPreviews']);
    //lấy file theo subject
    Route::get('/subjects', [FileController::class, 'getSubjects']);
    // Lấy file đầy đủ theo ID
    Route::get('/full/{id}', [FileController::class, 'getFullFile']);
    Route::delete('/{id}', [FileController::class, 'deleteDocument']);
    Route::get('getFile', function(){
          $doc = Document::with('user:id,name')->get();
    // Format lại `created_at`
    $formattedDocs = $doc->map(function ($document) {
        return [
            'id' => $document->Dcm_id,
            'subject' => $document->subject,
            'title' => $document->dcm_title,
            'user' => $document->user, // Giữ nguyên thông tin user
            'created_at' => $document->created_at->format('d/m/Y')
        ];
    });

    return response()->json($formattedDocs, 200);     
    });
});


// Bài thi
Route::prefix('/exams')->group(function () {
    // Danh sách và chi tiết bài thi
    Route::get('/', [ExamController::class, 'index']);
    Route::get('/{id}', [ExamController::class, 'show']);
    Route::post('/', [ExamController::class, 'create']);
    Route::post('/upload', [ExamController::class, 'createFromDocx']);
    // Bắt đầu bài thi
    Route::post('/start/{id}', [ExamController::class, 'startExam']);
    // Lấy bài thi theo môn học
    Route::prefix('/{examId}/comments')->group(function () {
        Route::get('/', [CommentController::class, 'index']);  
        Route::post('/', [CommentController::class, 'store']);   
    });
    // Nộp bài thi và lấy kết quả
    Route::post('/{id}/submit', [ExamResultController::class, 'submit']);
});


//Note Controller 
    Route::get('/notes', [NoteController::class, 'index']); // Lấy danh sách ghi chú
    Route::post('notes', [NoteController::class, 'store']); // Thêm ghi chú mới
    Route::get('/notes/{id}', [NoteController::class, 'show']); // Xem chi tiết một ghi chú
    Route::put('/notes/{id}', [NoteController::class, 'update']); // Cập nhật ghi chú
    Route::delete('/notes/{id}', [NoteController::class, 'destroy']); 

//Apponiterment Controller
Route::prefix('/appointments')->middleware('auth')->group(function () {
    Route::post('/', [AppointmentController::class, 'store']);
    Route::get('/', [AppointmentController::class, 'show']);
    Route::put('/{id}', [AppointmentController::class, 'update']);
    Route::delete('/{id}', [AppointmentController::class, 'destroy']);
});

//Favorite Document
Route::prefix('/favorites')->group(function () {
    Route::post('/', [FavoriteDocument::class, 'store']);
    Route::delete('/', [FavoriteDocument::class, 'destroy']);
    Route::get('/', [FavoriteDocument::class, 'index']);
    Route::post('/check', [FavoriteDocument::class, 'checkFavorite']);
});