<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function findByEmail(string $email): ?User;
    public function findById(int $id): ?User;
    public function findByUsernameOrEmail(string $login): ?User;
    public function isAdmin(User $user): bool;
    public function all(): \Illuminate\Support\Collection;
    public function setAdmin(User $user, bool $isAdmin): bool;
}