<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\Task\TaskResourceCollection;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class TaskController extends Controller
{
    public function __construct(public TaskService $taskService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tasks = $this->taskService->getTasks(auth()->id(), request()->query('per_page', 10), request()->query('page', 1));

            return response()->success(new TaskResourceCollection($tasks), 'Tasks retrieved successfully');
        } catch (Throwable $exception) {
            report($exception);

            return response()->error('An error occurred while retrieving tasks', $exception->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskRequest $request)
    {
        try {
            $task = $this->taskService->create($request->validated());

            return response()->success(new TaskResource($task), 'Task created successfully', ResponseAlias::HTTP_CREATED);
        } catch (Throwable $exception) {
            report($exception);

            return response()->error('An error occurred while creating your task', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $task = $this->taskService->getTask($id);

            throw_if(! $task, new ModelNotFoundException('Requested task not found.', ResponseAlias::HTTP_NOT_FOUND));

            return response()->success(new TaskResource($task), 'Task retrieved successfully');
        } catch (ModelNotFoundException $exception) {
            return response()->error($exception->getMessage(), $exception->getCode());
        } catch (Throwable $exception) {
            report($exception);

            return response()->error('An error occurred while retrieving your task', $exception->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        try {
            $task = $this->taskService->update($request->validated(), $id);

            throw_if(! $task, new ModelNotFoundException('Requested task not found.', ResponseAlias::HTTP_NOT_FOUND));

            return response()->success(new TaskResource($task), 'Task updated successfully');
        } catch (ModelNotFoundException $exception) {
            return response()->error($exception->getMessage(), $exception->getCode());
        } catch (Throwable $exception) {
            report($exception);

            return response()->error('An error occurred while updating your task', $exception->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->taskService->delete($id);

            return response()->success([], 'Task deleted successfully');
        } catch (Throwable $exception) {
            report($exception);

            return response()->error('An error occurred while deleting your task', $exception->getCode());
        }
    }
}
