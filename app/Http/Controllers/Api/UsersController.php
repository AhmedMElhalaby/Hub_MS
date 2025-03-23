<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UsersController extends ApiController
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $users = $this->userRepository->getAllPaginated(
            $request->search,
            'created_at',
            'desc',
            $request->per_page ?? 15
        );

        return $this->successResponse(
            $this->paginateResponse(UserResource::collection($users), $users)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = $this->userRepository->create($validated);

        return $this->successResponse(new UserResource($user));
    }

    public function show($id)
    {
        $user = $this->userRepository->findById($id);
        return $this->successResponse(new UserResource($user));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
        ]);

        $user = $this->userRepository->update($id, $validated);

        return $this->successResponse(new UserResource($user));
    }

    public function destroy($id)
    {
        $this->userRepository->delete($id);
        return $this->successResponse([], 'User deleted successfully');
    }
}