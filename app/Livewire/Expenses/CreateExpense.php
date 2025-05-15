<?php

namespace App\Livewire\Expenses;

use App\Repositories\ExpenseRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use App\Enums\ExpenseCategory;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\On;

class CreateExpense extends Component
{
    use WithModal, NotificationService;

    public $category = '';
    public $amount = '';

    protected ExpenseRepository $expenseRepository;

    public function boot(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function store()
    {
        $validated = $this->validate([
            'category' => ['required', new Enum(ExpenseCategory::class)],
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $this->expenseRepository->create($validated);
            $this->reset();
            $this->closeModal();
            $this->dispatch('expense-created');
            $this->notifySuccess(__('crud.expenses.messages.created'));
        } catch (\Exception $e) {
            $this->notifyError(__('crud.common.messages.error', ['model' => __('crud.expenses.model.singular')]));
        }
    }

    public function render()
    {
        return view('livewire.expenses.create-expense', [
            'categories' => ExpenseCategory::cases()
        ]);
    }

    #[On('open-create-expense')]
    public function open()
    {
        $this->openModal();
    }
}
