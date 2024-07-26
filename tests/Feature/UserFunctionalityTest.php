<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testStoreUserSuccess()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson(route('users.store'), $userData);

        $response->assertCreated()
            ->assertJson([
                'status' => 'success',
                'message' => 'User account created successfully.',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);
    }

    public function testShowUserSuccess()
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $response = $this->withToken($user->createToken('test-device')->plainTextToken)->getJson("/api/v1/users/{$userId}");

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'User account retrieved successfully.',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);
    }

    public function testShowUserNotFound()
    {
        $user = User::factory()->create();

        $userId = 999;

        $response = $this->withToken($user->createToken('test-device')->plainTextToken)->getJson(route('users.show', $userId));

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Requested user account not found.',
            ])
            ->assertJsonStructure([
                'status',
                'message',
            ]);
    }

    public function testUpdateUserSuccess()
    {
        $user = User::factory()->create();
        $userId = $user->id;
        $updateData = ['name' => 'Updated Name', 'email' => fake()->email()];

        $response = $this->withToken($user->createToken('test-device')->plainTextToken)->putJson(route('users.update', $userId), $updateData);

        $response->assertOk()
            ->assertJson([
                'message' => 'User account updated successfully.',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'name',
                ],
            ]);
    }

    public function testUpdateUserNotFound()
    {
        $userId = 999;
        $updateData = ['name' => 'Updated Name', 'email' => fake()->email()];
        $user = User::factory()->create();

        $response = $this->withToken($user->createToken('test-device')->plainTextToken)->putJson(route('users.update', $userId), $updateData);

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Requested user account not found.',
            ])
            ->assertJsonStructure([
                'status', 'message',
            ]);
    }
}
