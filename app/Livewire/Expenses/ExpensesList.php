<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use App\Traits\WithModal;
use App\Traits\WithSorting;
use App\Enums\ExpenseCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rules\Enum;
use App\Enums\FinanceType;
use App\Models\Finance;

#[Layout('components.layouts.app')]
class ExpensesList extends Component
{
    use WithPagination, WithSorting, WithModal;

    public $category = '';
    public $amount = '';
    public $expenseId;

    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    public function edit(Expense $expense)
    {
        $this->expenseId = $expense->id;
        $this->category = $expense->category->value;
        $this->amount = $expense->amount;
        $this->openModal();
    }

    public function confirmDelete(Expense $expense)
    {
        $this->expenseId = $expense->id;
        $expense->finances()->delete();
        $this->openDeleteModal();
    }

    public function resetForm()
    {
        $this->reset([
            'expenseId',
            'category',
            'amount',
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'category' => ['required', new Enum(ExpenseCategory::class)],
            'amount' => 'required|numeric|min:0',
        ]);

        if ($this->expenseId) {
            Expense::findOrFail($this->expenseId)->update($validated);
            Finance::updateOrCreate(
                ['expense_id' => $this->expenseId],
                [
                    'amount' => $validated['amount'],
                    'type' => FinanceType::Expense,
                    'note' => @$validated['note']
                ]
            );
        } else {
            $Expense = Expense::create($validated);
            $Expense->finances()->create([
                'amount' => $Expense->amount,
                'type' => FinanceType::Expense,
                'note' => $Expense->note
            ]);
        }

        session()->flash('message', __('Expense saved successfully.'));
        $this->closeModal();
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function delete()
    {
        try {
            $expense = Expense::findOrFail($this->expenseId);
            $expense->delete();
            session()->flash('message', __('Expense deleted successfully.'));
            $this->closeDeleteModal();
        } catch (\Exception $e) {
            session()->flash('error', __('An error occurred while deleting the expense.'));
        }
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function render()
    {
        return view('livewire.expenses.expenses-list', [
            'expenses' => Expense::when($this->search, function($query) {
                $query->where('category', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage),
            'categories' => ExpenseCategory::cases()
        ]);
    }
}
