<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Sanctum-protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn() => auth()->user());

    // Task routes (you can use apiResource if you've set up the controller)
    Route::apiResource('tasks', TaskController::class);

    // Example of extra authenticated endpoints
    Route::post('/tasks/reorder', [TaskController::class, 'reorder']);
});