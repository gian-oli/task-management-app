<?php

namespace App\Services\Interfaces;

use Illuminate\Support\Collection;
use App\Models\Task;

interface TaskServiceInterface
{
    public function getUserTasks(int $userId): Collection;
    public function createTask(array $data): Task;
    public function updateTask(int $taskId, array $data): bool;
    public function deleteTask(int $taskId): bool;
    public function reorderTasks(int $userId, array $orderedTaskIds): bool;
}