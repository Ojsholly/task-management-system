<?php

namespace App\Services;

use App\Contracts\UserServiceInterface;
use App\Models\User;

class UserService implements UserServiceInterface
{
    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function getUserByField(string $field, string $value): ?User
    {
        return User::where($field, $value)->first();
    }
}
