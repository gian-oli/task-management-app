<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Support\Collection;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function allByUser(int $userId): Collection
    {
        return Task::where('user_id', $userId)->orderBy('order')->get();
    }
    
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function delete(int $taskId): bool
    {
        $task = Task::find($taskId);

        if (!$task) {
            return false;
        }

        return $task->delete();
    }

    public function reorder(int $userId, array $orderedTaskIds): bool
    {
        DB::beginTransaction();

        try {
            foreach ($orderedTaskIds as $index => $taskId) {
                Task::where('id', $taskId)
                    ->where('user_id', $userId)
                    ->update(['order' => $index]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function update(int $taskId, array $data): bool
    {
        $task = Task::find($taskId);

        if (!$task) {
            return false;
        }

        return $task->update($data);
    }

    public function findById(int $taskId): ?Task
    {
        return Task::find($taskId);
    }

    public function getTasksByUser(int $userId): Collection
    {
        return Task::where('user_id', $userId)->orderBy('order')->get();
    }
}
