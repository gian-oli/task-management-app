<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\TaskServiceInterface;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ResponseTrait;

    protected TaskServiceInterface $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(): JsonResponse
    {
        $tasks = $this->taskService->getUserTasks(auth()->id());
        return $this->returnResponse([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks,
        ]);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->createTask($request->validated());

            return $this->returnResponse([
                'status' => 'success',
                'status_code' => 201,
                'message' => 'Task created successfully',
                'data' => $task,
            ]);
        } catch (\Exception $e) {
            return $this->returnResponse($this->errorResponse($e));
        }
    }

    public function show(int $id): JsonResponse
    {
       $task = $this->taskService->getTaskById($id); 

        if (!$task) {
            return $this->returnResponse($this->modelNotFoundResponse($id));
        }

        return $this->returnResponse([
            'status' => 'success',
            'status_code' => 200,
            'message' => 'Task retrieved successfully',
            'data' => $task,
        ]);
    }

    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        try {
            $updated = $this->taskService->updateTask($id, $request->validated());

            if (!$updated) {
                return $this->returnResponse($this->modelNotFoundResponse($id));
            }

            return $this->returnResponse($this->successResponse('Task updated successfully'));
        } catch (\Exception $e) {
            return $this->returnResponse($this->errorResponse($e));
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->taskService->deleteTask($id);

            if (!$deleted) {
                return $this->returnResponse($this->modelNotFoundResponse($id));
            }

            return $this->returnResponse($this->successResponse('Task deleted successfully'));
        } catch (\Exception $e) {
            return $this->returnResponse($this->errorResponse($e));
        }
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'ordered_task_ids' => 'required|array',
            'ordered_task_ids.*' => 'integer|exists:tasks,id',
        ]);

        try {
            $userId = auth()->id();
            $result = $this->taskService->reorderTasks($userId, $request->input('ordered_task_ids'));

            if (!$result) {
                return $this->returnResponse([
                    'status' => 'warning',
                    'status_code' => 400,
                    'message' => 'Task reordering failed.',
                ]);
            }

            return $this->returnResponse($this->successResponse('Tasks reordered successfully'));
        } catch (\Exception $e) {
            return $this->returnResponse($this->errorResponse($e));
        }
    }
}
