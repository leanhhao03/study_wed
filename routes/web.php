<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Auth\Events\Login;

// Route cho trang chủ
Route::get('/', function () {
    return view('home');
})->name('home');

// Route cho trang đăng nhập
Route::get('/login', function () {
    return view('login');
})->name('login');

// Route để xử lý yêu cầu đăng nhập

