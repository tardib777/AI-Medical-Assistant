<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function(){
    Route::get('/register',[AuthController::class,'registerPage'])->name('register');
    Route::post('/register',[AuthController::class,'register']);
    Route::get('/login',[AuthController::class,'loginPage'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return Inertia::render('Home',['message' => 'Hello from Inertia + Laravel']);
    })->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
Route::get('/',function(){
    return redirect()->route('home');
});


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
