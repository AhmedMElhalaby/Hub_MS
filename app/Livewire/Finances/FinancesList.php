<?php

namespace App\Livewire\Finances;

use App\Enums\BookingStatus;
use App\Models\Finance;
use App\Enums\FinanceType;
use App\Models\Booking;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\WithSorting;
use Livewire\Attributes\Layout;
use League\Csv\Writer;

#[Layout('components.layouts.app')]
class FinancesList extends Component
{
    use WithPagination, WithSorting;

    public $search = '';
    public $typeFilter = '';
    public $dateFilter = '';
    public $paymentMethodFilter = '';
    public $statusFilter = '';
    public $selectedFinance;
    public $showVoidModal = false;
    public $showExportModal = false;
    public $selectedColumns = [];

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
            ->when($this->paymentMethodFilter, function ($query) {
                $query->where('payment_method', $this->paymentMethodFilter);
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'voided') {
                    $query->whereNotNull('note')->where('note', 'like', 'Voided:%');
                } elseif ($this->statusFilter === 'active') {
                    $query->where(function ($q) {
                        $q->whereNull('note')
                            ->orWhere('note', 'not like', 'Voided:%');
                    });
                }
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
                    case 'last_month':
                        $query->whereBetween('created_at', [
                            now()->subMonth()->startOfMonth(),
                            now()->subMonth()->endOfMonth()
                        ]);
                        break;
                }
            });

        return view('livewire.finances.finances-list', [
            'finances' => $query->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
            'statistics' => $this->getStatistics(),
        ]);
    }

    public function voidPayment(Finance $finance)
    {
        $this->selectedFinance = $finance;
        $this->showVoidModal = true;
    }

    public function confirmVoid()
    {
        if ($this->selectedFinance->booking) {
            $this->selectedFinance->booking->increment('balance', $this->selectedFinance->amount);
        }

        $this->selectedFinance->update(['note' => 'Voided: ' . ($this->selectedFinance->note ?? '')]);

        $this->showVoidModal = false;
        session()->flash('message', __('crud.finances.messages.voided'));
    }

    public function getStatistics()
    {
        return [
            'total_income' => Finance::where('type', FinanceType::Income)->sum('amount'),
            'total_expense' => Finance::where('type', FinanceType::Expense)->sum('amount'),
            'total_expected_payment' => Booking::whereNotIn('status', [BookingStatus::Completed, BookingStatus::Cancelled])
                ->sum('balance'),
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

    public function export()
    {
        $finances = Finance::with(['booking.customer', 'expense'])
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
            ->when($this->paymentMethodFilter, function ($query) {
                $query->where('payment_method', $this->paymentMethodFilter);
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'voided') {
                    $query->whereNotNull('note')->where('note', 'like', 'Voided:%');
                } elseif ($this->statusFilter === 'active') {
                    $query->where(function ($q) {
                        $q->whereNull('note')
                            ->orWhere('note', 'not like', 'Voided:%');
                    });
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        $csv = Writer::createFromString('');

        // Add headers
        $csv->insertOne([
            __('crud.finances.fields.type'),
            __('crud.finances.fields.reference'),
            __('crud.finances.fields.amount'),
            __('crud.finances.fields.note'),
            __('crud.finances.fields.date'),
            __('crud.finances.fields.payment_method'),
            __('crud.finances.fields.status')
        ]);

        // Add data
        foreach ($finances as $finance) {
            $reference = $finance->booking
                ? $finance->booking->customer->name
                : ($finance->expense ? $finance->expense->title : '');

            $csv->insertOne([
                $finance->type->label(),
                $reference,
                number_format($finance->amount, 2),
                $finance->note,
                $finance->created_at->format('M d, Y H:i'),
                $finance->payment_method->label(),
                str_contains($finance->note ?? '', 'Voided:') ? 'Voided' : 'Active'
            ]);
        }

        $filename = 'finances-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($csv) {
            echo $csv->toString();
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function mount()
    {
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';

        // Default selected columns
        $this->selectedColumns = [
            'type',
            'reference',
            'amount',
            'date',
            'payment_method',
            'status'
        ];
    }

    public function exportSelected()
    {
        $finances = Finance::with(['booking.customer', 'expense'])
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
            ->when($this->paymentMethodFilter, function ($query) {
                $query->where('payment_method', $this->paymentMethodFilter);
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'voided') {
                    $query->whereNotNull('note')->where('note', 'like', 'Voided:%');
                } elseif ($this->statusFilter === 'active') {
                    $query->where(function ($q) {
                        $q->whereNull('note')
                            ->orWhere('note', 'not like', 'Voided:%');
                    });
                }
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
                    case 'last_month':
                        $query->whereBetween('created_at', [
                            now()->subMonth()->startOfMonth(),
                            now()->subMonth()->endOfMonth()
                        ]);
                        break;
                }
            })->get();

        $csv = Writer::createFromString('');

        // Add selected headers
        $headers = [];
        foreach ($this->selectedColumns as $column) {
            $headers[] = __('crud.finances.fields.' . $column);
        }
        $csv->insertOne($headers);

        // Add data
        foreach ($finances as $finance) {
            $row = [];
            foreach ($this->selectedColumns as $column) {
                switch ($column) {
                    case 'type':
                        $row[] = $finance->type->label();
                        break;
                    case 'reference':
                        $row[] = $finance->booking
                            ? $finance->booking->customer->name
                            : ($finance->expense ? $finance->expense->title : '');
                        break;
                    case 'amount':
                        $row[] = number_format($finance->amount, 2);
                        break;
                    case 'note':
                        $row[] = $finance->note ?? '';
                        break;
                    case 'date':
                        $row[] = $finance->created_at->format('M d, Y H:i');
                        break;
                    case 'payment_method':
                        $row[] = $finance->payment_method->label();
                        break;
                    case 'status':
                        $row[] = str_contains($finance->note ?? '', 'Voided:') ? 'Voided' : 'Active';
                        break;
                }
            }
            $csv->insertOne($row);
        }

        $this->showExportModal = false;

        return response()->streamDownload(function () use ($csv) {
            echo $csv->toString();
        }, 'finances-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
