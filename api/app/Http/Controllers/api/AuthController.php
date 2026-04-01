<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->only('email', 'password'));

        if (!$result) {
            return $this->error('Invalid credential', [
                'email' => ['Email atau password salah']
            ], 401);
        }

        return $this->success([
            'token' => $result['token'],
            'user'  => new UserResource($result['user'])
        ], 'Login berhasil');
    }

    public function me(Request $request)
    {
        return $this->success(
            new UserResource($request->user()),
            'User data'
        );
    }
}
