<?php

// app/Repositories/Interfaces/TaskRepositoryInterface.php
namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;
use App\Models\Task;

interface TaskRepositoryInterface
{
    public function create(array $data): Task;

    public function delete(int $taskId): bool;

    public function reorder(int $userId, array $orderedTaskIds): bool;

    public function update(int $taskId, array $data): bool;

    public function findById(int $taskId): ?Task;

    public function getTasksByUser(int $userId): Collection;
}
