<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Document\FileController;
use App\Http\Controllers\Auth\ModifyAuthenticate;
use App\Http\Controllers\Document\CommentController;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\Exam\ExamResultController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\Verify\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [AuthenticateController::class, 'LoginUser']);
    Route::post('edit', [ModifyAuthenticate::class, 'modifyProfile']);
});

//Mail Controller
Route::post('/forgot-password', [MailController::class, 'sendEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

//file controller
Route::prefix('documents')->group(function () {
    // Upload và chuyển đổi file
    Route::post('/upload', [FileController::class, 'uploadAndConvert'])->name('documents.upload');
    // Lấy danh sách tất cả các file đã upload
    Route::get('/all', [FileController::class, 'getFile'])->name('documents.getAll');
    //get preview
    Route::get('/preview', [FileController::class, 'getPreviews']);
    //lấy file theo subject
    Route::get('/subjects', [FileController::class, 'getSubjects']);
    // Lấy file đầy đủ theo ID
    Route::get('/full/{id}', [FileController::class, 'getFullFile'])->name('documents.getFull');
});


// Bài thi
Route::prefix('/exams')->group(function () {
    // Danh sách và chi tiết bài thi
    Route::get('/', [ExamController::class, 'index']);
    Route::get('/{id}', [ExamController::class, 'show']);
    Route::post('/', [ExamController::class, 'create']);
    // Bắt đầu bài thi
    Route::post('/{id}/start', [ExamController::class, 'startExam']);
    // Lấy bài thi theo môn học
    Route::get('/subject/{subject}', [ExamController::class, 'getBySubject']);
    // Bình luận cho Exam
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
    Route::delete('/notes/{id}', [NoteController::class, 'destroy']); // Xóa ghi chú

//Apponiterment Controller
Route::prefix('/appointments')->group(function () {
    Route::get('/', [AppointmentController::class, 'index']);
    Route::post('/', [AppointmentController::class, 'store']);
    Route::get('/{id}', [AppointmentController::class, 'show']);
    Route::put('/{id}', [AppointmentController::class, 'update']);
    Route::delete('/{id}', [AppointmentController::class, 'destroy']);
});

