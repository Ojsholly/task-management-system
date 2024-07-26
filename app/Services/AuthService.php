<?php

namespace App\Services;

use App\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;
use SensitiveParameter;

class AuthService implements AuthServiceInterface
{
    public function __construct(public UserService $userService) {}

    /**
     * Authenticates a user by email and password.
     *
     * @param  string  $email  The user's email.
     * @param  string  $password  The user's password.
     * @param  string  $deviceName  The name of the device.
     * @return array|null An array containing the token and user if successful, null otherwise.
     */
    public function login(string $email, #[SensitiveParameter] string $password, string $deviceName): ?array
    {
        // Get the user by email
        $user = $this->userService->getUserByField('email', $email);

        // Check if the user exists
        return match ($user) {
            null => null,                                                       // Return null if the user does not exist
            default => match (Hash::check($password, $user->password)) {
                true => [                                                       // Return an array containing the token and user if the password is correct
                    'token' => $user->createToken($deviceName)->plainTextToken,
                    'user' => $user,
                ],
                default => null,                                                // Return null if the password is incorrect
            },
        };
    }

    public function logout(): void
    {
        auth()->user()?->currentAccessToken()?->delete();
    }

    public function logoutAllDevices(): void
    {
        auth()->user()?->tokens()->delete();
    }
}
