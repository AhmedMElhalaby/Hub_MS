<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CustomerResource;
use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;

class CustomersController extends ApiController
{
    protected CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        $customers = $this->customerRepository->getAllPaginated(
            $request->search,
            'created_at',
            'desc',
            $request->per_page ?? 15
        );

        return $this->successResponse(
            $this->paginateResponse(CustomerResource::collection($customers), $customers)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'mobile' => 'required|string|unique:customers,mobile',
            'address' => 'nullable|string',
            'specialization' => 'required|numeric'
        ]);

        $customer = $this->customerRepository->create($validated);

        return $this->successResponse(new CustomerResource($customer));
    }

    public function show($id)
    {
        $customer = $this->customerRepository->findWithBookings($id);
        return $this->successResponse(new CustomerResource($customer));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
            'mobile' => 'required|string|unique:customers,mobile,' . $id,
            'address' => 'nullable|string',
            'specialization' => 'required|numeric'
        ]);

        $customer = $this->customerRepository->update($id, $validated);

        return $this->successResponse(new CustomerResource($customer));
    }

    public function destroy($id)
    {
        $this->customerRepository->delete($id);
        return $this->successResponse([], 'Customer deleted successfully');
    }
}