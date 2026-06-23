<?php

namespace App\Livewire\Booking;

use App\Models\Booking;
use App\Models\Hike;
use App\Models\Member;
use App\Models\PickupPoint;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class BookingForm extends Component
{
    public int    $hikeId;
    public int    $spots          = 1;
    public string $package        = 'day';
    public ?int   $pickupPointId  = null;
    public bool   $wantsTransport = false;
    public string $notes          = '';

    public function mount(int $hikeId): void
    {
        $this->hikeId = $hikeId;

        // default package based on trip type
        if (($this->hike->nights ?? 0) > 0) {
            $this->package = 'stay';
        }
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
    public function isOvernight(): bool
    {
        return ($this->hike->nights ?? 0) > 0;
    }

    #[Computed]
    public function pickupPoints()
    {
        return $this->hike->pickupPoints;
    }

    #[Computed]
    public function spotsRemaining(): int
    {
        return $this->hike->spots_remaining;
    }

    #[Computed]
    public function discountPct(): float
    {
        return (float) ($this->member->explorerLevel?->discount_percentage ?? 0);
    }

    #[Computed]
    public function ticketFee(): float
    {
        $base = $this->hike->price * $this->spots;
        return round($base - ($base * $this->discountPct / 100), 2);
    }

    #[Computed]
    public function discountAmount(): float
    {
        $base = $this->hike->price * $this->spots;
        return round($base * $this->discountPct / 100, 2);
    }

    #[Computed]
    public function accommodationFee(): float
    {
        if (! $this->isOvernight) return 0.0;
        if (! in_array($this->package, ['stay', 'full'])) return 0.0;
        return round((float) $this->hike->accommodation_cost_per_person * $this->hike->nights * $this->spots, 2);
    }

    #[Computed]
    public function transportFee(): float
    {
        $needsTransport = $this->package === 'full' || ($this->package === 'day' && $this->wantsTransport);
        if (! $needsTransport || ! $this->hike->includes_transport) return 0.0;
        return round((float) $this->hike->transport_fee * $this->spots, 2);
    }

    #[Computed]
    public function amountDue(): float
    {
        return $this->ticketFee + $this->accommodationFee + $this->transportFee;
    }

    public function updatedSpots(): void
    {
        $this->spots = max(1, min($this->spots, $this->spotsRemaining));
        unset($this->ticketFee, $this->discountAmount, $this->accommodationFee, $this->transportFee, $this->amountDue);
    }

    public function updatedPackage(): void
    {
        // Reset transport/pickup when package changes
        if ($this->package !== 'full') {
            if (! $this->isOvernight) {
                $this->wantsTransport = false;
                $this->pickupPointId  = null;
            }
        }
        unset($this->accommodationFee, $this->transportFee, $this->amountDue);
    }

    public function updatedWantsTransport(): void
    {
        if (! $this->wantsTransport) {
            $this->pickupPointId = null;
        }
        unset($this->transportFee, $this->amountDue);
    }

    public function submit(): void
    {
        $isOvernightFull = $this->isOvernight && $this->package === 'full';
        $wantsPickup     = $isOvernightFull || (! $this->isOvernight && $this->wantsTransport);

        $rules = [
            'spots'   => ['required', 'integer', 'min:1', 'max:' . $this->spotsRemaining],
            'package' => ['required', 'in:day,stay,full'],
            'notes'   => ['nullable', 'string', 'max:500'],
        ];

        if ($wantsPickup && $this->hike->includes_transport) {
            $rules['pickupPointId'] = [
                'required',
                'integer',
                function ($attr, $value, $fail) {
                    $point = $this->hike->pickupPoints->find($value);
                    if (! $point) { $fail('Invalid pickup point.'); return; }
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
            $this->spots,
            $this->package
        );

        $updates = [];

        if ($this->notes) {
            $updates['notes'] = $this->notes;
        }

        if ($wantsPickup && $this->pickupPointId) {
            $updates['pickup_point_id']       = $this->pickupPointId;
            $updates['transport_fee_applied']  = $this->transportFee;
            $updates['amount_due']             = $this->amountDue;
        } elseif (! $wantsPickup) {
            $updates['amount_due'] = $this->amountDue;
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
