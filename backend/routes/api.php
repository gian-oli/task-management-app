<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class);

    // For reordering tasks (custom endpoint)
    Route::post('tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
});