<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Collection;

class UserService implements UserServiceInterface
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getAllUsers(): Collection
    {
        return $this->userRepo->all();
    }

    public function toggleAdminRole(int $userId): bool
    {
        $user = $this->userRepo->findById($userId);

        if (!$user) {
            return false;
        }

        return $this->userRepo->setAdmin($user, !$user->is_admin);
    }

    public function findUserById(int $id): ?User
    {
        return $this->userRepo->findById($id);
    }
}
