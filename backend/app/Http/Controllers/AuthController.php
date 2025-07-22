<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ResponseTrait;

    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->validated());
            return $this->returnResponse([
                'status' => 'success',
                'status_code' => 201,
                'message' => 'User registered successfully.',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return $this->returnResponse($this->errorResponse($e));
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->authService->login($request->validated());
            return $this->returnResponse([
                'status' => 'success',
                'status_code' => 200,
                'message' => 'Login successful.',
                'data' => $data,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->returnResponse($this->failedValidationResponse($e->errors()));
        } catch (\Exception $e) {
            return $this->returnResponse($this->errorResponseAuthentication($e->getMessage()));
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();
            return $this->returnResponse($this->successResponse('Logged out successfully.'));
        } catch (\Exception $e) {
            return $this->returnResponse($this->errorResponse($e));
        }
    }
}
