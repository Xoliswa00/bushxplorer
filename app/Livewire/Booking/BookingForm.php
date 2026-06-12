<?php

namespace App\Livewire\Booking;

use App\Models\Booking;
use App\Models\Hike;
use App\Models\Member;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;

class BookingForm extends Component
{
    public int $hikeId;
    public int $spots = 1;

    #[Rule('required|string|max:500')]
    public string $notes = '';

    public ?Booking $booking = null;

    public function mount(int $hikeId): void
    {
        $this->hikeId = $hikeId;
    }

    #[Computed]
    public function hike(): Hike
    {
        return Hike::findOrFail($this->hikeId);
    }

    #[Computed]
    public function member(): Member
    {
        return Member::where('user_id', Auth::id())->firstOrFail();
    }

    #[Computed]
    public function amountDue(): float
    {
        $discount = $this->member->explorerLevel?->discount_percentage ?? 0;
        $base     = $this->hike->price * $this->spots;
        return round($base - ($base * $discount / 100), 2);
    }

    #[Computed]
    public function discountAmount(): float
    {
        $discount = $this->member->explorerLevel?->discount_percentage ?? 0;
        $base     = $this->hike->price * $this->spots;
        return round($base * $discount / 100, 2);
    }

    #[Computed]
    public function spotsRemaining(): int
    {
        return $this->hike->spots_remaining;
    }

    public function updatedSpots(): void
    {
        $this->spots = max(1, min($this->spots, $this->spotsRemaining));
    }

    public function submit(): void
    {
        $this->validate([
            'spots' => ['required', 'integer', 'min:1', 'max:' . $this->spotsRemaining],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $booking = app(BookingService::class)->createDraft(
            $this->member,
            $this->hike,
            $this->spots
        );

        if ($this->notes) {
            $booking->update(['notes' => $this->notes]);
        }

        app(BookingService::class)->submitForPayment($booking);

        $this->booking = $booking->refresh();

        $this->dispatch('booking-created', bookingId: $booking->id);
        $this->redirect(route('booking.payment', $booking->booking_ref));
    }

    public function render()
    {
        return view('livewire.booking.booking-form');
    }
}
