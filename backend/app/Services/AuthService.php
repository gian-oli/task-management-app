<?php

namespace App\Services;

use App\Services\Interfaces\AuthServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): \App\Models\User
    {
        // Password hashing happens here before saving
        $data['password'] = bcrypt($data['password']);
        $data['role'] = $data['role'] ?? 'user'; // Default role

        return $this->userRepository->create($data);
    }

    public function login(array $credentials): array
    {
        $login = $credentials['login'] ?? '';
        $user = $this->userRepository->findByUsernameOrEmail($login);

        if (!$user || !Hash::check((string) $credentials['password'], (string) $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'user' => $user,
            'token' => $user->createToken('auth-token')->plainTextToken,
        ];
    }



    public function logout(): void
    {
        auth()->user()->tokens()->delete();
    }
}
