<?php

namespace App\Contracts;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

interface TaskServiceInterface
{
    public function getTasks(?int $userId = null, int $perPage = 10, int $page = 1): LengthAwarePaginator;

    public function getTask(int $id): ?Task;

    public function create(array $data): Task;

    public function update(array $data, int $id): ?Task;

    public function delete(int $id): void;
}
