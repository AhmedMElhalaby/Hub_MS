<?php

namespace App\Livewire\Expenses;

use App\Repositories\ExpenseRepository;
use App\Services\NotificationService;
use Livewire\Component;
use App\Traits\WithModal;
use Livewire\Attributes\On;

class DeleteExpense extends Component
{
    use WithModal, NotificationService;

    public $expenseId;

    protected ExpenseRepository $expenseRepository;

    public function boot(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function delete()
    {
        try {
            $this->expenseRepository->delete($this->expenseId);
            $this->notifySuccess('messages.expense.deleted');
            $this->dispatch('expense-deleted');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->notifyError('messages.expense.delete_error');
        }
    }

    public function render()
    {
        return view('livewire.expenses.delete-expense');
    }

    #[On('open-delete-expense')]
    public function open($expenseId)
    {
        $this->expenseId = $expenseId;
        $this->openModal();
    }
}
