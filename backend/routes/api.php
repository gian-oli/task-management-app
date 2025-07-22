<?php

use App\Http\Controllers\Admin\UserManagementController;
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

        // Task Management (Admin)
        Route::apiResource('tasks', TaskController::class);
        Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->middleware('check.pusher');
        Route::post('/tasks/{id}/restore', [TaskController::class, 'restore']);
        Route::delete('/tasks/{id}/force', [TaskController::class, 'forceDelete']);

        // User Management (Admin Only)
        Route::get('/admin/users', [UserManagementController::class, 'index']);
        Route::post('/admin/users/{id}/toggle-admin', [UserManagementController::class, 'toggleAdmin']);
    });

    // User routes — limited access
    Route::middleware('is_user')->group(function () {
        Route::apiResource('tasks', TaskController::class)->except(['forceDelete', 'restore']);
        Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->middleware('check.pusher');
    });

});
