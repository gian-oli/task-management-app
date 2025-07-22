<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Sanctum-protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn() => auth()->user());

    // Admin routes — full access
    Route::middleware('is_admin')->group(function () {
        Route::apiResource('tasks', TaskController::class);

        // Protect reorder route with check.pusher middleware
        Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->middleware('check.pusher');

        Route::post('/tasks/{id}/restore', [TaskController::class, 'restore']);
        Route::delete('/tasks/{id}/force', [TaskController::class, 'forceDelete']);
    });

    // User routes — limited access (no forceDelete)
    Route::middleware('is_user')->group(function () {
        // User cannot force delete or restore
        Route::apiResource('tasks', TaskController::class)->except(['forceDelete', 'restore']);

        // Protect reorder route with check.pusher middleware
        Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->middleware('check.pusher');
    });

});
