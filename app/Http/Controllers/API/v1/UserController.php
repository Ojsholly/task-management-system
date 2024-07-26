<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class UserController extends Controller
{
    public function __construct(public UserService $userService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());

            $data = new UserResource($user);

            return response()->success($data, 'User account created successfully.', ResponseAlias::HTTP_CREATED);
        } catch (Throwable $exception) {
            report($exception);

            return response()->error('An error occurred while creating your account. Please try again later.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = $this->userService->getUserByField('id', $id);

            throw_if(! $user, ModelNotFoundException::class, 'Requested user account not found.', ResponseAlias::HTTP_NOT_FOUND);

            return response()->success(new UserResource($user), 'User account retrieved successfully.');
        } catch (ModelNotFoundException $exception) {
            return response()->error($exception->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        } catch (Throwable $exception) {
            report($exception);

            return response()->error('An error occurred. Please try again later.', $exception->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            $user = $this->userService->updateUser($request->validated(), $id);

            throw_if(! $user, ModelNotFoundException::class, 'Requested user account not found.', ResponseAlias::HTTP_NOT_FOUND);

            return response()->success(new UserResource($user), 'User account updated successfully.');
        } catch (ModelNotFoundException $exception) {
            return response()->error($exception->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
        } catch (Throwable $exception) {
            report($exception);

            return response()->error('An error occurred. Please try again later.', $exception->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
