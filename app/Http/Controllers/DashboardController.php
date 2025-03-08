<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Finance;
use App\Models\Workspace;
use App\Enums\BookingStatus;
use App\Enums\FinanceType;
use App\Enums\WorkspaceStatus;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $months = collect(range(1, 12))->map(function($month) {
            return now()->startOfYear()->addMonths($month-1)->format('M');
        });


        return view('dashboard', [
            'totalRevenue' => Finance::where('type', FinanceType::Income)->sum('amount'),
            'activeBookings' => Booking::where('status', BookingStatus::Confirmed)->count(),
            'availableWorkspaces' => Workspace::where('status', WorkspaceStatus::Available)->count(),
            'recentBookings' => Booking::with(['customer', 'workspace'])
                ->latest()
                ->take(5)
                ->get(),
            'recentFinances' => Finance::latest()
                ->take(5)
                ->get(),
            'months' => $months->toArray()
        ]);
    }
}
