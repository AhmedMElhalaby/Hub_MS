<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ExpenseResource;
use App\Repositories\ExpenseRepository;
use Illuminate\Http\Request;
use App\Enums\ExpenseCategory;
use Illuminate\Validation\Rules\Enum;

class ExpensesController extends ApiController
{
    protected ExpenseRepository $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function index(Request $request)
    {
        $expenses = $this->expenseRepository->getAllPaginated(
            $request->search,
            'created_at',
            'desc',
            $request->per_page ?? 15
        );

        return $this->successResponse(
            $this->paginateResponse(ExpenseResource::collection($expenses), $expenses)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['required', new Enum(ExpenseCategory::class)],
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string'
        ]);

        $expense = $this->expenseRepository->create($validated);

        return $this->successResponse(new ExpenseResource($expense));
    }

    public function show($id)
    {
        $expense = $this->expenseRepository->findWithFinances($id);
        return $this->successResponse(new ExpenseResource($expense));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category' => ['required', new Enum(ExpenseCategory::class)],
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string'
        ]);

        $expense = $this->expenseRepository->update($id, $validated);

        return $this->successResponse(new ExpenseResource($expense));
    }

    public function destroy($id)
    {
        $this->expenseRepository->delete($id);
        return $this->successResponse([], 'Expense deleted successfully');
    }
}