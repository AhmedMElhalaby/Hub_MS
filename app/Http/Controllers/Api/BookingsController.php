<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\BookingResource;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;
use App\Enums\PaymentMethod;
use Illuminate\Validation\Rules\Enum;

class BookingsController extends ApiController
{
    protected BookingRepository $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function index(Request $request)
    {
        $bookings = $this->bookingRepository->getActiveBookings(
            $request->search,
            $request->status,
            $request->date_filter
        )->paginate($request->per_page ?? 15);

        return $this->successResponse(
            $this->paginateResponse(BookingResource::collection($bookings), $bookings)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'workspace_id' => 'required|exists:workspaces,id',
            'plan_id' => 'required|exists:plans,id',
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
            'total' => 'required|numeric|min:0',
        ]);

        $booking = $this->bookingRepository->create($validated);

        return $this->successResponse(new BookingResource($booking));
    }

    public function show($id)
    {
        $booking = $this->bookingRepository->findWithRelations($id);
        return $this->successResponse(new BookingResource($booking));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'workspace_id' => 'required|exists:workspaces,id',
            'plan_id' => 'required|exists:plans,id',
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
            'total' => 'required|numeric|min:0',
        ]);

        $booking = $this->bookingRepository->update($id, $validated);

        return $this->successResponse(new BookingResource($booking));
    }

    public function confirm($id)
    {
        $booking = $this->bookingRepository->confirm($id);
        return $this->successResponse(new BookingResource($booking));
    }

    public function cancel($id)
    {
        $booking = $this->bookingRepository->cancel($id);
        return $this->successResponse(new BookingResource($booking));
    }

    public function addPayment(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => ['required', new Enum(PaymentMethod::class)],
        ]);

        $payment = $this->bookingRepository->addPayment(
            $id,
            $validated['amount'],
            $validated['payment_method']
        );

        return $this->successResponse(['payment' => $payment]);
    }

    public function renew(Request $request, $id)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
            'additional_cost' => 'required|numeric|min:0',
        ]);

        $booking = $this->bookingRepository->renew($id, $validated);

        return $this->successResponse(new BookingResource($booking));
    }
}