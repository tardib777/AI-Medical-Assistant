<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::put('/profile/update',[ProfileController::class,'update']);
    Route::get('/profile/show',[ProfileController::class,'show']);
    Route::post('/chat/send', [ChatController::class, 'chat'])->middleware('medical.session');

});
