<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\UserServiceInterface;
use App\Traits\ResponseTrait;
use Exception;

class UserManagementController extends Controller
{
    use ResponseTrait;

    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('is_admin');
        $this->userService = $userService;
    }

    public function index()
    {
        try {
            $users = $this->userService->getAllUsers();

            return $this->returnResponse([
                'status' => 'success',
                'status_code' => 200,
                'data' => $users,
                'message' => 'Users retrieved successfully'
            ]);

        } catch (Exception $e) {
            return $this->returnResponse($this->errorResponse($e));
        }
    }

    public function toggleAdmin($id)
    {
        try {
            $success = $this->userService->toggleAdminRole($id);

            if (!$success) {
                return $this->returnResponse($this->modelNotFoundResponse($id));
            }

            return $this->returnResponse($this->successResponse('User role updated successfully'));

        } catch (Exception $e) {
            return $this->returnResponse($this->errorResponse($e));
        }
    }
}
