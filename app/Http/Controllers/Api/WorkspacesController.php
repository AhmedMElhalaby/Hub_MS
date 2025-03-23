<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\WorkspaceResource;
use App\Repositories\WorkspaceRepository;
use Illuminate\Http\Request;
use App\Enums\WorkspaceStatus;
use Illuminate\Validation\Rules\Enum;

class WorkspacesController extends ApiController
{
    protected WorkspaceRepository $workspaceRepository;

    public function __construct(WorkspaceRepository $workspaceRepository)
    {
        $this->workspaceRepository = $workspaceRepository;
    }

    public function index(Request $request)
    {
        $workspaces = $this->workspaceRepository->getAllPaginated(
            $request->search,
            'created_at',
            'desc',
            $request->per_page ?? 15
        );

        return $this->successResponse(
            $this->paginateResponse(WorkspaceResource::collection($workspaces), $workspaces)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'desk' => 'required|numeric',
            'status' => ['required', new Enum(WorkspaceStatus::class)],
        ]);

        $workspace = $this->workspaceRepository->create($validated);

        return $this->successResponse(new WorkspaceResource($workspace));
    }

    public function show($id)
    {
        $workspace = $this->workspaceRepository->findWithBookings($id);
        return $this->successResponse(new WorkspaceResource($workspace));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'desk' => 'required|numeric',
            'status' => ['required', new Enum(WorkspaceStatus::class)],
        ]);

        $workspace = $this->workspaceRepository->update($id, $validated);

        return $this->successResponse(new WorkspaceResource($workspace));
    }

    public function destroy($id)
    {
        $this->workspaceRepository->delete($id);
        return $this->successResponse([], 'Workspace deleted successfully');
    }

    public function available()
    {
        $workspaces = $this->workspaceRepository->getAvailable();
        return $this->successResponse(WorkspaceResource::collection($workspaces));
    }
}