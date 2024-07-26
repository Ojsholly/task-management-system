<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService) {}

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $response = $this->authService->login($credentials['email'], $credentials['password'], $credentials['device_name']);

        return match ($response == null) {
            true => response()->error('Invalid credentials submitted.'),
            false => response()->success(['token' => $response['token'], 'user' => new UserResource($response['user'])], 'Logged in successfully.'),
        };
    }

    public function logout()
    {
        $this->authService->logout();

        return response()->success([], 'Logged out successfully.');
    }

    public function logoutAllDevices()
    {
        $this->authService->logoutAllDevices();

        return response()->success([], 'Logged out from all devices successfully.');
    }
}
