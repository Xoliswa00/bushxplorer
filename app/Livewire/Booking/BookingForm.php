<?php

namespace App\Livewire\Booking;

use App\Models\Booking;
use App\Models\Hike;
use App\Models\Member;
use App\Models\PickupPoint;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class BookingForm extends Component
{
    public int  $hikeId;
    public int  $spots          = 1;
    public ?int $pickupPointId  = null;
    public bool $wantsTransport = false;
    public string $notes        = '';

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
    public function member(): Member
    {
        return Member::where('user_id', Auth::id())->firstOrFail();
    }

    #[Computed]
    public function pickupPoints()
    {
        return $this->hike->pickupPoints;
    }

    #[Computed]
    public function selectedPickup(): ?PickupPoint
    {
        return $this->pickupPointId
            ? $this->hike->pickupPoints->find($this->pickupPointId)
            : null;
    }

    #[Computed]
    public function spotsRemaining(): int
    {
        return $this->hike->spots_remaining;
    }

    #[Computed]
    public function hikeFee(): float
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
    public function transportFee(): float
    {
        if (! $this->wantsTransport || ! $this->hike->includes_transport) return 0.0;
        return (float) $this->hike->transport_fee * $this->spots;
    }

    #[Computed]
    public function amountDue(): float
    {
        return $this->hikeFee + $this->transportFee;
    }

    public function updatedSpots(): void
    {
        $this->spots = max(1, min($this->spots, $this->spotsRemaining));
    }

    public function updatedWantsTransport(): void
    {
        if (! $this->wantsTransport) {
            $this->pickupPointId = null;
        }
    }

    public function submit(): void
    {
        $rules = [
            'spots' => ['required', 'integer', 'min:1', 'max:' . $this->spotsRemaining],
            'notes' => ['nullable', 'string', 'max:500'],
        ];

        if ($this->hike->includes_transport && $this->wantsTransport) {
            $rules['pickupPointId'] = [
                'required',
                'integer',
                function ($attr, $value, $fail) {
                    $point = $this->hike->pickupPoints->find($value);
                    if (! $point) {
                        $fail('Invalid pickup point.');
                        return;
                    }
                    if ($point->seats_remaining < $this->spots) {
                        $fail("Only {$point->seats_remaining} seat(s) left at {$point->name}.");
                    }
                },
            ];
        }

        $this->validate($rules);

        $booking = app(BookingService::class)->createDraft(
            $this->member,
            $this->hike,
            $this->spots
        );

        $updates = [];

        if ($this->notes) {
            $updates['notes'] = $this->notes;
        }

        if ($this->wantsTransport && $this->pickupPointId) {
            $updates['pickup_point_id']      = $this->pickupPointId;
            $updates['transport_fee_applied'] = $this->transportFee;
            $updates['amount_due']            = $this->amountDue;
        }

        if ($updates) {
            $booking->update($updates);
        }

        app(BookingService::class)->submitForPayment($booking);

        $this->dispatch('booking-created', bookingId: $booking->id);
        $this->redirect(route('booking.payment', $booking->booking_ref));
    }

    public function render()
    {
        return view('livewire.booking.booking-form');
    }
}
