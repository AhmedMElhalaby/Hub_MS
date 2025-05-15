<?php

namespace App\Livewire\Expenses;

use App\Repositories\ExpenseRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use App\Enums\ExpenseCategory;
use Livewire\Attributes\On;
use Illuminate\Validation\Rules\Enum;

class EditExpense extends Component
{
    use WithModal, NotificationService;

    public $expenseId;
    public $category = '';
    public $amount = '';

    protected ExpenseRepository $expenseRepository;

    public function boot(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function update()
    {
        $validated = $this->validate([
            'category' => ['required', new Enum(ExpenseCategory::class)],
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $this->expenseRepository->update($this->expenseId, $validated);
            $this->reset();
            $this->closeModal();
            $this->dispatch('expense-updated');
            $this->notifySuccess(__('crud.expenses.messages.updated'));
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.error', ['model' => __('crud.expenses.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.expenses.edit-expense', [
            'categories' => ExpenseCategory::cases()
        ]);
    }

    #[On('open-edit-expense')]
    public function open($expenseId)
    {
        $expense = $this->expenseRepository->findById($expenseId);
        $this->expenseId = $expense->id;
        $this->category = $expense->category->value;
        $this->amount = $expense->amount;
        $this->openModal();
    }
}
