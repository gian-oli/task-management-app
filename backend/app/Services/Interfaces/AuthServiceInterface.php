<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function register(Request $request): mixed;
    public function login(Request $request): mixed;
    public function logout(Request $request): mixed;
}
