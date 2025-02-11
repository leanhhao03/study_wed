<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Document\FileController;
use App\Http\Controllers\Auth\ModifyAuthenticate;
use App\Http\Controllers\Exam\ExamController;
use App\Http\Controllers\Exam\ExamResultController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [AuthenticateController::class, 'LoginUser']);
    Route::post('edit', [ModifyAuthenticate::class, 'modifyProfile']);
});

//Mail Controller
Route::post('/send-email', [MailController::class, 'sendEmail']);

//file controller
Route::post('/upload', [FileController::class, 'upload']);
Route::post('/convert', [FileController::class, 'convert']);
Route::get('/file/{id}', [FileController::class, 'getFile']);


//Exam controller
Route::get('exams', [ExamController::class, 'index']);
Route::get('exams/{id}/start', [ExamController::class, 'startExam'])->middleware('auth:sanctum');
Route::post('exams/{id}/submit', [ExamResultController::class, 'submit'])->middleware('auth:sanctum');
Route::get('exams/{id}', [ExamController::class, 'show']); // Xem bài thi
Route::post('exams', [ExamController::class, 'create']); // Tạo bài thi


//Note Controller 
    Route::get('/notes', [NoteController::class, 'index']); // Lấy danh sách ghi chú
    Route::post('notes', [NoteController::class, 'store']); // Thêm ghi chú mới
    Route::get('/notes/{id}', [NoteController::class, 'show']); // Xem chi tiết một ghi chú
    Route::put('/notes/{id}', [NoteController::class, 'update']); // Cập nhật ghi chú
    Route::delete('/notes/{id}', [NoteController::class, 'destroy']); // Xóa ghi chú


