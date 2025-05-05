<?php

namespace App\Livewire\Bookings;

use App\Models\Plan;
use App\Repositories\BookingRepository;
use App\Services\NotificationService;
use App\Traits\WithModal;
use App\Enums\BookingStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Traits\WithSorting;
use App\Traits\WithTenantContext;
use Livewire\Attributes\On;

#[Layout('components.layouts.app')]
class BookingsList extends Component
{
    use WithPagination, WithModal, WithSorting, NotificationService, WithTenantContext;

    protected BookingRepository $bookingRepository;

    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';
    public $dateType = 'created';
    public $dateFrom = '';
    public $dateTo = '';
    public $planFilter = '';

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->dateFilter = '';
        $this->dateType = 'created';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->planFilter = '';
    }

    public function boot(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function render()
    {
        return view('livewire.bookings.bookings-list', [
            'bookings' => $this->bookingRepository->getActiveBookings(
                $this->search,
                $this->statusFilter,
                [
                    'type' => $this->dateType,
                    'from' => $this->dateFrom,
                    'to' => $this->dateTo,
                ],
                $this->planFilter
            )->paginate(10),
            'statuses' => BookingStatus::cases(),
            'dateTypes' => [
                'created' => __('Created Date'),
                'start' => __('Start Date'),
                'end' => __('End Date'),
            ],
            'plans' => Plan::all(),
        ]);
    }

    #[On('refresh')]
    public function refresh()
    {
        $this->render();
    }
}
