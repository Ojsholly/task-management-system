<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $taskService;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testIndex()
    {
        $user = User::factory()->create();
        $tasks = Task::factory()->for($user)->count(10)->create();

        $response = $this->withToken($user->createToken('test-device')->plainTextToken)->getJson(route('tasks.index'));

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Tasks retrieved successfully',
            ]);
    }

    public function testStore()
    {
        $user = User::factory()->create();
        $taskData = ['title' => 'New Task', 'user_id' => 1, 'description' => 'This is a new task.'];

        $response = $this->withToken($user->createToken('test-device')->plainTextToken)->postJson(route('tasks.store'), $taskData);

        $response->assertCreated()
            ->assertJson([
                'message' => 'Task created successfully',
            ])
            ->assertJsonStructure([
                'status', 'message', 'data',
            ]);
    }

    public function testShow()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $taskId = $task->id;

        $response = $this->withToken($user->createToken('test-device')->plainTextToken)->getJson(route('tasks.show', $taskId));

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Task retrieved successfully',
            ])
            ->assertJsonStructure([
                'status', 'message', 'data',
            ]);
    }

    public function testShowNotFound()
    {
        $user = User::factory()->create();
        $taskId = 999;

        $response = $this->withToken($user->createToken('test-device')->plainTextToken)->getJson("/api/v1/tasks/{$taskId}");

        $response->assertNotFound()
            ->assertJson([
                'status' => 'error',
                'message' => 'Requested task not found.',
            ])
            ->assertJsonStructure([
                'status', 'message',
            ]);
    }

    public function testUpdate()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $taskId = $task->id;
        $updateData = ['title' => 'Updated Task', 'description' => 'This is an updated task.', 'completed_at' => now()->subDay()->toDateTimeString()];

        $response = $this->withToken($user->createToken('test-device')->plainTextToken)->putJson(route('tasks.update', $taskId), $updateData);

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Task updated successfully',
            ]);
    }

    public function testDestroy()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();
        $taskId = $task->id;

        $response = $this->withToken($user->createToken('test-device')->plainTextToken)->deleteJson("/api/v1/tasks/{$taskId}");

        $response->assertOk()
            ->assertJson([
                'status' => 'success',
                'message' => 'Task deleted successfully',
            ])
            ->assertJsonStructure([
                'status', 'message', 'data',
            ]);
    }
}
