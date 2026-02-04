<?php

use Illuminate\Support\Facades\Route;

// Auth API routes
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [App\Http\Controllers\Api\AuthController::class, 'user'])->middleware('auth:sanctum');

// Password Reset API routes
Route::post('/forgot-password', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [App\Http\Controllers\Api\AuthController::class, 'resetPassword']);

// Notes API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notes', [App\Http\Controllers\Api\NoteController::class, 'index']);
    Route::post('/notes', [App\Http\Controllers\Api\NoteController::class, 'store']);
    Route::get('/notes/{note}', [App\Http\Controllers\Api\NoteController::class, 'show']);
    Route::put('/notes/{note}', [App\Http\Controllers\Api\NoteController::class, 'update']);
    Route::delete('/notes/{note}', [App\Http\Controllers\Api\NoteController::class, 'destroy']);
    Route::post('/notes/{note}/voice', [App\Http\Controllers\Api\NoteController::class, 'uploadVoice']);
});
