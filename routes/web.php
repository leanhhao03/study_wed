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


//Route document
Route::get('/documents', function(){
    return view('document');
});

Route::get('/tests', function(){
    return view('test');
});
Route::get('/test-doc', function(){
    return view('testdoc');
});
Route::get('/reset-password', function(){
    return view('reset_password');
});

Route::get('/profile', function(){
    return view('ProfileUser');
});

Route::get('/calendar', function () {
    return view('calendar');
});

Route::get('/notes', function () {
    return view('note');
});