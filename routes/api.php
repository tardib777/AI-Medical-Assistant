<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorProfileController;
use App\Http\Controllers\AppointmentController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::put('/profile/update',[ProfileController::class,'update']);
    Route::get('/profile/show',[ProfileController::class,'show']);
    Route::post('/chat/send', [ChatController::class, 'chat'])->middleware('medical.session');

    Route::middleware('role:Admin')->group(function () {
        Route::post('/admin/doctors', [DoctorProfileController::class, 'store']);
    });

    Route::middleware('role:Doctor')->group(function () {
        Route::post('/doctor/appointments', [AppointmentController::class, 'store']);
        Route::get('/doctor/appointments', [AppointmentController::class, 'myAppointments']);
        Route::delete('/doctor/appointments/{appointment}', [AppointmentController::class, 'cancelSlot']);
    });

    Route::middleware('role:Patient')->group(function () {
        Route::get('/doctors', [AppointmentController::class, 'listDoctors']);
        Route::get('/doctors/{doctorProfile}/appointments', [AppointmentController::class, 'availableSlots']);
        Route::post('/appointments/{appointment}/book', [AppointmentController::class, 'book']);
        Route::get('/patient/appointments', [AppointmentController::class, 'myBookings']);
        Route::delete('/patient/appointments/{appointment}', [AppointmentController::class, 'cancelBooking']);
    });
});
