<?php

namespace App\Livewire\Finances;

use App\Models\Finance;
use App\Enums\FinanceType;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\WithSorting;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class FinancesList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $typeFilter = '';
    public $dateFilter = '';
    public $selectedFinance;
    public $showVoidModal = false;

    public function voidPayment(Finance $finance)
    {
        $this->selectedFinance = $finance;
        $this->showVoidModal = true;
    }

    public function confirmVoid()
    {
        if ($this->selectedFinance->booking) {
            // Update booking balance
            $this->selectedFinance->booking->increment('balance', $this->selectedFinance->amount);
        }

        // Mark finance record as voided
        $this->selectedFinance->update(['note' => 'Voided: ' . ($this->selectedFinance->note ?? '')]);

        $this->showVoidModal = false;
        session()->flash('message', __('Payment voided successfully.'));
    }

    public function getStatistics()
    {
        return [
            'total_income' => Finance::where('type', FinanceType::Income)->sum('amount'),
            'total_expense' => Finance::where('type', FinanceType::Expense)->sum('amount'),
            'net_amount' => Finance::where('type', FinanceType::Income)->sum('amount') -
                          Finance::where('type', FinanceType::Expense)->sum('amount'),
            'monthly_income' => Finance::where('type', FinanceType::Income)
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'monthly_expense' => Finance::where('type', FinanceType::Expense)
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];
    }

    public function render()
    {
        $query = Finance::with(['booking.customer', 'expense'])
            ->when($this->search, function ($query) {
                $query->whereHas('booking.customer', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('expense', function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->when($this->dateFilter, function ($query) {
                switch ($this->dateFilter) {
                    case 'today':
                        $query->whereDate('created_at', today());
                        break;
                    case 'week':
                        $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'month':
                        $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                        break;
                }
            });

        return view('livewire.finances.finances-list', [
            'finances' => $query->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
            'statistics' => $this->getStatistics(),
        ]);
    }
}
