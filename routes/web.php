<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/users', function () {
    return User::all();
});

Route::post('/users', function (Request $request) {
    $user = User::create($request->all());
    return response()->json($user);
});
