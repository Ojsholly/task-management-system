<?php

namespace App\Contracts;

use App\Models\User;

interface UserServiceInterface
{
    public function createUser(array $data): User;

    public function getUserByField(string $field, string $value): ?User;
}
