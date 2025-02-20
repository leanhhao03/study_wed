<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\User;
use Illuminate\Auth\Events\Login;

// Route cho trang chủ
Route::get('/', function () {
    return view('home');
})->name('home');

// Route cho trang đăng nhập
Route::get('/login', function(){
    return view('Login');
})->name('login')->middleware('web');
//Route cho trang đăng ký
Route::get('/register', function () {
    return view('register');
});

//logout
Route::post('/logout', [AuthenticateController::class, 'LogoutUser'])->middleware('auth'); 

//Route document
Route::get('/documents', function(){
    return view('document');
});

Route::get('/tests', function(){
    return view('test');
});


