<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class ExpenseDetails extends Component
{
    public Expense $expense;

    public function mount(Expense $expense)
    {
        $this->expense = $expense->load('finances');
    }

    public function render()
    {
        return view('livewire.expenses.expense-details');
    }
}
