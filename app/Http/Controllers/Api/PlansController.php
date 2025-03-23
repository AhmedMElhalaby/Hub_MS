<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PlanResource;
use App\Repositories\PlanRepository;
use Illuminate\Http\Request;
use App\Enums\PlanType;
use Illuminate\Validation\Rules\Enum;

class PlansController extends ApiController
{
    protected PlanRepository $planRepository;

    public function __construct(PlanRepository $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    public function index(Request $request)
    {
        $plans = $this->planRepository->getAllPaginatedWithMikrotik(
            $request->search,
            'created_at',
            'desc',
            $request->per_page ?? 15
        );

        return $this->successResponse(
            $this->paginateResponse(PlanResource::collection($plans), $plans)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', new Enum(PlanType::class)],
            'price' => 'required|numeric|min:0',
            'mikrotik_profile' => 'nullable|string'
        ]);

        $plan = $this->planRepository->create($validated);

        return $this->successResponse(new PlanResource($plan));
    }

    public function show($id)
    {
        $plan = $this->planRepository->findWithBookings($id);
        return $this->successResponse(new PlanResource($plan));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => ['required', new Enum(PlanType::class)],
            'price' => 'required|numeric|min:0',
            'mikrotik_profile' => 'nullable|string'
        ]);

        $plan = $this->planRepository->update($id, $validated);

        return $this->successResponse(new PlanResource($plan));
    }

    public function destroy($id)
    {
        $this->planRepository->delete($id);
        return $this->successResponse([], 'Plan deleted successfully');
    }
}