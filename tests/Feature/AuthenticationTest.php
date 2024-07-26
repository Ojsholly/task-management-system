<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private function createUser()
    {
        return User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
    }

    public function testLoginSuccess()
    {
        $user = $this->createUser();
        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password123',
            'device_name' => 'test-device',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token', 'user',
                ],
            ]);
    }

    public function testLoginFailure()
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
            'device_name' => 'test-device',
        ]);

        $response->assertBadRequest()
            ->assertJson([
                'message' => 'Invalid credentials submitted.',
            ])
            ->assertJsonStructure([
                'status', 'message',
            ]);
    }

    public function testLogout()
    {
        $user = $this->createUser();
        $token = $user->createToken('test-device')->plainTextToken;

        $response = $this->withToken($token)->postJson(route('auth.logout'));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged out successfully.',
            ])
            ->assertJsonStructure([
                'status', 'message', 'data',
            ]);
    }

    public function testLogoutAllDevices()
    {
        $user = $this->createUser();
        $token = $user->createToken('test-device')->plainTextToken;

        $response = $this->withToken($token)->postJson(route('auth.logout-all-devices'));

        $response->assertOk()
            ->assertJson([
                'message' => 'Logged out from all devices successfully.',
            ])
            ->assertJsonStructure([
                'status', 'message', 'data',
            ]);
    }
}
