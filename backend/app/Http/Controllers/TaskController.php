<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\TaskServiceInterface;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected TaskServiceInterface $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
        // You can also add middleware here if needed, e.g.
        // $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the tasks for the authenticated user.
     */
    public function index(): JsonResponse
    {
        $tasks = $this->taskService->getUserTasks(auth()->id());
        return response()->json($tasks);
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());
        return response()->json($task, 201);
    }

    /**
     * Display the specified task by id.
     */
    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        return response()->json($task);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $updated = $this->taskService->updateTask($id, $request->validated());

        if (!$updated) {
            return response()->json(['message' => 'Task not found or update failed'], 404);
        }

        return response()->json(['message' => 'Task updated successfully']);
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->taskService->deleteTask($id);

        if (!$deleted) {
            return response()->json(['message' => 'Task not found or delete failed'], 404);
        }

        return response()->json(['message' => 'Task deleted successfully']);
    }

    /**
     * Optional: Reorder tasks endpoint (if you have task reordering).
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'ordered_task_ids' => 'required|array',
            'ordered_task_ids.*' => 'integer|exists:tasks,id',
        ]);

        $userId = auth()->id();

        $result = $this->taskService->reorderTasks($userId, $request->input('ordered_task_ids'));

        if (!$result) {
            return response()->json(['message' => 'Reordering failed'], 400);
        }

        return response()->json(['message' => 'Tasks reordered successfully']);
    }
}
