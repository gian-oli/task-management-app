<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function getTasksByUser(int $userId): Collection
    {
        return Task::where('user_id', $userId)->orderBy('order')->get();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function findById(int $id): ?Task
    {
        return Task::find($id);
    }

    public function update(int $taskId, array $data): bool
    {
        $task = Task::find($taskId);

        if (!$task) {
            return false;
        }

        return $task->update($data);
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
}
