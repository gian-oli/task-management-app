<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function register(array $data): mixed;
    public function login(array $credentials): mixed;
    public function logout(): void;
}
