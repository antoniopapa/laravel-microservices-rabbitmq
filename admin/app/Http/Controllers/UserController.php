<?php

namespace App\Http\Controllers;


use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Jobs\AdminAdded;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Microservices\UserService;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $this->userService->allows('view', 'users');

        return $this->userService->all($request->input('page', 1));
    }

    public function show($id)
    {
        $this->userService->allows('view', "users");

        $user = $this->userService->get($id);

        return new UserResource($user);
    }

    public function store(UserCreateRequest $request)
    {
        $this->userService->allows('edit', "users");

        $data = $request->only('first_name', 'last_name', 'email') + ['password' => 1234];

        $user = $this->userService->create($data);

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id'),
        ]);

        AdminAdded::dispatch($user->email);

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $this->userService->allows('edit', "users");

        $user = $this->userService->update($id, $request->only('first_name', 'last_name', 'email'));

        UserRole::where('user_id', $user->id)->delete();

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id'),
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        $this->userService->allows('edit', "users");

        $this->userService->delete($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
