<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('regiter', RegisterController::class);
Route::apiResource('authenticate', AuthenticateController::class);
