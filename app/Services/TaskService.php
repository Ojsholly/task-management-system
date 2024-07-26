<?php

namespace App\Services;

use App\Contracts\TaskServiceInterface;
use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService implements TaskServiceInterface
{
    public function getTasks(?int $userId = null, int $perPage = 10, int $page = 1): LengthAwarePaginator
    {
        return Task::when($userId, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->paginate($perPage, ['*'], 'page', $page);
    }

    public function getTask(int $id): ?Task
    {
        return Task::find($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(array $data, int $id): ?Task
    {
        $task = $this->getTask($id);
        $task?->update($data);

        return $task;
    }

    public function delete(int $id): void
    {
        $task = $this->getTask($id);
        $task?->delete();
    }
}
