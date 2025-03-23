<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Repositories\ExpenseRepository;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class ExpenseDetails extends Component
{
    use NotificationService;

    public $expense;
    protected ExpenseRepository $expenseRepository;

    public function boot(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function mount(Expense $expense)
    {
        try {
            $this->expense = $this->expenseRepository->findWithFinances($expense->id);
        } catch (\Exception $e) {
            $this->notifyError('messages.expense.not_found');
            return $this->redirect(route('expenses.index'));
        }
    }

    public function render()
    {
        return view('livewire.expenses.expense-details');
    }
}
