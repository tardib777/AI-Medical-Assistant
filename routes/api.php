<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:Admin')->group(function () {
        Route::post('/doctors/add', [DoctorController::class, 'store']);
        Route::put('/doctors/update/{doctor_id}', [DoctorController::class, 'update']);
        Route::delete('/doctors/delete/{doctor_id}', [DoctorController::class, 'destroy']);
    });
    Route::middleware('role:Doctor')->group(function () {
        Route::get('/appointments', [AppointmentController::class, 'index']);
        Route::post('/appointments/add', [AppointmentController::class, 'store']);
        Route::put('/appointments/update/{appointment_id}', [AppointmentController::class, 'update']);
        Route::delete('/appointments/delete/{appointment_id}', [AppointmentController::class, 'destroy']);
    });
    Route::post('/logout', [UserController::class, 'logout']);
    Route::put('/profile/update',[ProfileController::class,'update']);
    Route::get('/profile/show',[ProfileController::class,'show']);
    Route::post('/chat/send', [ChatController::class, 'chat'])->middleware('medical.session');

});
