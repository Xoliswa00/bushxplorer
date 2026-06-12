<?php

namespace App\Livewire\Booking;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BookingConfirmation extends Component
{
    public string $bookingRef;

    public function mount(string $bookingRef): void
    {
        $this->bookingRef = $bookingRef;
    }

    #[Computed]
    public function booking(): Booking
    {
        $memberId = \App\Models\Member::where('user_id', Auth::id())->value('id');

        return Booking::with(['hike', 'member.explorerLevel', 'payments'])
            ->where('booking_ref', $this->bookingRef)
            ->where('member_id', $memberId)
            ->firstOrFail();
    }

    #[Computed]
    public function statusLabel(): string
    {
        return match ($this->booking->status) {
            'draft'            => 'Draft',
            'pending_payment'  => 'Awaiting Payment',
            'payment_uploaded' => 'Payment Under Review',
            'payment_verified' => 'Payment Verified',
            'confirmed'        => 'Confirmed',
            'attended'         => 'Attended',
            'cancelled'        => 'Cancelled',
            'refunded'         => 'Refunded',
            default            => ucfirst($this->booking->status),
        };
    }

    #[Computed]
    public function statusColor(): string
    {
        return match ($this->booking->status) {
            'confirmed', 'attended'                => 'green',
            'payment_uploaded', 'payment_verified' => 'blue',
            'pending_payment'                       => 'yellow',
            'cancelled', 'refunded'                => 'red',
            default                                => 'gray',
        };
    }

    public function render()
    {
        return view('livewire.booking.booking-confirmation');
    }
}
