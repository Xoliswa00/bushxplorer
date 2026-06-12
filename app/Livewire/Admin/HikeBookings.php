<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\Hike;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class HikeBookings extends Component
{
    public int    $hikeId;
    public string $filterStatus = 'all';

    public function mount(int $hikeId): void
    {
        $this->hikeId = $hikeId;
    }

    #[Computed]
    public function hike(): Hike
    {
        return Hike::with('pickupPoints')->findOrFail($this->hikeId);
    }

    #[Computed]
    public function bookings()
    {
        return Booking::with(['member.explorerLevel', 'payments', 'pickupPoint'])
            ->where('hike_id', $this->hikeId)
            ->when($this->filterStatus !== 'all', fn ($q) => $q->where('status', $this->filterStatus))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed]
    public function stats(): array
    {
        $all = Booking::where('hike_id', $this->hikeId)
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->get();

        return [
            'booked'   => $all->sum('spots'),
            'capacity' => $this->hike->max_capacity,
            'revenue'  => $all->whereIn('status', ['payment_verified', 'confirmed', 'attended'])->sum('amount_due'),
            'pending'  => $all->where('status', 'payment_uploaded')->count(),
            'confirmed'=> $all->whereIn('status', ['confirmed', 'attended'])->count(),
        ];
    }

    public function verifyAndConfirm(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        $service = app(BookingService::class);

        if ($booking->status === Booking::STATUS_PAYMENT_UPLOADED) {
            $service->verifyPayment($booking, Auth::id());
            $booking->refresh();
        }

        if ($booking->status === Booking::STATUS_PAYMENT_VERIFIED) {
            $service->confirm($booking);
        }

        unset($this->bookings, $this->stats);
    }

    public function rejectPayment(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        app(BookingService::class)->rejectPayment($booking, 'Rejected by admin — please re-upload proof.');
        unset($this->bookings, $this->stats);
    }

    public function markAttended(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        if ($booking->status === Booking::STATUS_CONFIRMED) {
            app(BookingService::class)->markAttended($booking);
        }
        unset($this->bookings, $this->stats);
    }

    public function render()
    {
        return view('livewire.admin.hike-bookings');
    }
}
