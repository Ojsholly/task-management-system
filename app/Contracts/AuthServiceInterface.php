<?php

namespace App\Contracts;

interface AuthServiceInterface
{
    public function login(string $email, string $password, string $deviceName): ?array;

    public function logout(): void;

    public function logoutAllDevices(): void;
}
