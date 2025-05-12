<?php

namespace App\Livewire\Expenses;

use App\Repositories\ExpenseRepository;
use App\Traits\WithSorting;
use App\Enums\ExpenseCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class ExpensesList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $categoryFilter = '';

    protected ExpenseRepository $expenseRepository;

    public function boot(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function render()
    {
        return view('livewire.expenses.expenses-list', [
            'expenses' => $this->expenseRepository->getAllPaginated(
                [
                    'q' => $this->search,
                    'category' => $this->categoryFilter
                ],
                $this->sortField,
                $this->sortDirection,
                $this->perPage,
            ),
            'categories' => ExpenseCategory::cases()
        ]);
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->render();
    }

    public function mount()
    {
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
    }
}
