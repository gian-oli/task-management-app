<?php

namespace App\Services\Interfaces;

use App\Models\User;
use Illuminate\Support\Collection;

interface UserServiceInterface
{
    public function getAllUsers(): Collection;
    public function toggleAdminRole(int $userId): bool;
    public function findUserById(int $id): ?User;
}
