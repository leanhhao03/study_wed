<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/reset-password', function(){
    return view('forget_password');
});
Route::get('/reset-password2', function(){
    return view('reset_password');
});