<?php

namespace App\Livewire\Booking;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class PaymentUpload extends Component
{
    use WithFileUploads;

    public string $bookingRef;
    public string $paymentMethod = 'eft';
    public string $reference     = '';

    #[Rule(['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'])]
    public $proofFile = null;

    public function mount(string $bookingRef): void
    {
        $this->bookingRef = $bookingRef;
    }

    #[Computed]
    public function booking(): Booking
    {
        return Booking::where('booking_ref', $this->bookingRef)
            ->where('member_id', $this->member->id)
            ->firstOrFail();
    }

    #[Computed]
    public function member()
    {
        return \App\Models\Member::where('user_id', Auth::id())->firstOrFail();
    }

    #[Computed]
    public function bankDetails(): array
    {
        return [
            'bank'       => config('bushxplorer.bank_name', 'FNB'),
            'account'    => config('bushxplorer.bank_account', '62XXXXXXXXX'),
            'branch'     => config('bushxplorer.bank_branch', '250655'),
            'reference'  => $this->bookingRef,
        ];
    }

    public function upload(): void
    {
        $this->validate([
            'proofFile'     => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'paymentMethod' => ['required', 'in:eft,cash,card,other'],
            'reference'     => ['nullable', 'string', 'max:100'],
        ]);

        app(BookingService::class)->uploadPayment(
            $this->booking,
            $this->proofFile->getRealPath()
                ? $this->proofFile  // pass UploadedFile to service
                : $this->proofFile,
            $this->reference ?: null,
            $this->paymentMethod
        );

        $this->dispatch('payment-uploaded', bookingRef: $this->bookingRef);
        $this->redirect(route('booking.confirmation', $this->bookingRef));
    }

    public function render()
    {
        return view('livewire.booking.payment-upload');
    }
}
