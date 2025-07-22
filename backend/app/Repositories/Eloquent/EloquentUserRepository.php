<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByUsernameOrEmail(string $login): ?User
    {
        return User::where('username', $login)
            ->orWhere('email', $login)
            ->first();
    }

    public function isAdmin(User $user): bool
    {
        return (bool) $user->is_admin;
    }


    public function all(): \Illuminate\Support\Collection
    {
        return User::all();
    }

    public function setAdmin(User $user, bool $isAdmin): bool
    {
        $user->is_admin = $isAdmin;
        return $user->save();
    }
}
