<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $user = $this->authService->register($request);
        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        $data = $this->authService->login($request);
        return response()->json($data);
    }

    public function logout(Request $request)
    {
        $message = $this->authService->logout($request);
        return response()->json($message);
    }
}
