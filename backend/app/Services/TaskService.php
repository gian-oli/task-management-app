<?php
// app/Services/TaskService.php
namespace App\Services;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Services\Interfaces\TaskServiceInterface;
use App\Models\Task;
use Illuminate\Support\Collection;

class TaskService implements TaskServiceInterface
{
    protected TaskRepositoryInterface $taskRepo;

    public function __construct(TaskRepositoryInterface $taskRepo)
    {
        $this->taskRepo = $taskRepo;
    }

    public function getUserTasks(int $userId): Collection
    {
        return $this->taskRepo->getTasksByUser($userId);
    }

    public function createTask(array $data): Task
    {
        // Validate or manipulate $data if needed before creating

        // Create and return the task model
        return Task::create($data);
    }

    /**
     * Delete a task by its ID.
     */
    public function deleteTask(int $taskId): bool
    {
        $task = Task::find($taskId);

        if (!$task) {
            return false; // Task not found
        }

        return $task->delete();
    }

    /**
     * Reorder tasks for a user by updating their `order` field.
     *
     * $orderedTaskIds is an array of task IDs in the new order.
     */
    public function reorderTasks(int $userId, array $orderedTaskIds): bool
    {
        // Use transaction to ensure all orders update atomically
        DB::beginTransaction();

        try {
            foreach ($orderedTaskIds as $index => $taskId) {
                // Update only tasks belonging to the user for security
                Task::where('id', $taskId)
                    ->where('user_id', $userId)
                    ->update(['order' => $index]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // Optionally log $e->getMessage()
            return false;
        }
    }

    /**
     * Update an existing task by its ID with given data.
     */
    public function updateTask(int $taskId, array $data): bool
    {
        $task = Task::find($taskId);

        if (!$task) {
            return false; // Task not found
        }

        return $task->update($data);
    }
}
