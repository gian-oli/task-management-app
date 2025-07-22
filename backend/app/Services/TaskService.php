<?php

namespace App\Services;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Services\Interfaces\TaskServiceInterface;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskService implements TaskServiceInterface
{
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getUserTasks(int $userId): Collection
    {
        return $this->taskRepository->getTasksByUser($userId);
    }

    public function createTask(array $data): Task
    {
        return $this->taskRepository->create($data);
    }

    public function deleteTask(int $taskId): bool
    {
        return $this->taskRepository->delete($taskId);
    }

    public function reorderTasks(int $userId, array $orderedTaskIds): bool
    {
        return $this->taskRepository->reorder($userId, $orderedTaskIds);
    }

    public function updateTask(int $taskId, array $data): bool
    {
        return $this->taskRepository->update($taskId, $data);
    }

    public function getTaskById(int $taskId): ?Task
    {
        return $this->taskRepository->findById($taskId);
    }
}
