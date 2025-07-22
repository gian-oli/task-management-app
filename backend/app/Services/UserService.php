<?php

namespace App\Services;

use App\Services\Interfaces\UserServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserService implements UserServiceInterface
{
    protected UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function createUser(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        return $this->userRepo->create($data);
    }

    public function isAdmin(User $user): bool
    {
        return $user->is_admin;
    }
}
