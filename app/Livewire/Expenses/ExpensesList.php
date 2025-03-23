<?php

namespace App\Livewire\Expenses;

use App\Repositories\ExpenseRepository;
use App\Services\NotificationService;
use App\Traits\WithModal;
use App\Traits\WithSorting;
use App\Enums\ExpenseCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rules\Enum;

#[Layout('components.layouts.app')]
class ExpensesList extends Component
{
    use WithPagination, WithSorting, WithModal, NotificationService;

    public $category = '';
    public $amount = '';
    public $expenseId;

    protected ExpenseRepository $expenseRepository;

    public function boot(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function edit($expenseId)
    {
        $expense = $this->expenseRepository->findById($expenseId);
        $this->expenseId = $expense->id;
        $this->category = $expense->category->value;
        $this->amount = $expense->amount;
        $this->openModal();
    }

    public function confirmDelete($expenseId)
    {
        $this->expenseId = $expenseId;
        $this->openDeleteModal();
    }

    public function resetForm()
    {
        $this->reset(['expenseId', 'category', 'amount']);
    }

    public function save()
    {
        $validated = $this->validate([
            'category' => ['required', new Enum(ExpenseCategory::class)],
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            if ($this->expenseId) {
                $this->expenseRepository->update($this->expenseId, $validated);
            } else {
                $this->expenseRepository->create($validated);
            }

            $this->notifySuccess('messages.expense.saved');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.expense.save_error');
        }
    }

    public function delete()
    {
        try {
            $this->expenseRepository->delete($this->expenseId);
            $this->notifySuccess('messages.expense.deleted');
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.expense.delete_error');
        }
    }

    public function render()
    {
        return view('livewire.expenses.expenses-list', [
            'expenses' => $this->expenseRepository->getAllPaginated(
                $this->search,
                $this->sortField,
                $this->sortDirection,
                $this->perPage
            ),
            'categories' => ExpenseCategory::cases()
        ]);
    }
}
