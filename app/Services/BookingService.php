<?php

namespace App\Services;

use App\Mail\BookingConfirmedMail;
use App\Mail\PaymentRejectedMail;
use App\Models\Booking;
use App\Models\Hike;
use App\Models\Member;
use App\Models\MemberNotification;
use App\Models\Payment;
use App\Services\BadgeService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BookingService
{
    public function __construct(
        private readonly ExplorerPointsService $pointsService,
        private readonly BadgeService $badgeService,
    ) {}

    /**
     * Create a draft booking for a member on a hike.
     * package: 'day' | 'stay' | 'full'
     */
    public function createDraft(Member $member, Hike $hike, int $spots = 1, string $package = 'day'): Booking
    {
        return DB::transaction(function () use ($member, $hike, $spots, $package) {
            $discount  = $member->explorerLevel?->discount_percentage ?? 0;
            $baseAmount = $hike->price * $spots;
            $discountAmount = round($baseAmount * ($discount / 100), 2);
            $ticketFee = $baseAmount - $discountAmount;

            $accommodationFee = 0.0;
            if (in_array($package, ['stay', 'full']) && ($hike->nights ?? 0) > 0) {
                $accommodationFee = round((float) $hike->accommodation_cost_per_person * $hike->nights * $spots, 2);
            }

            $amountDue = $ticketFee + $accommodationFee;

            return Booking::create([
                'member_id'                 => $member->id,
                'hike_id'                   => $hike->id,
                'status'                    => Booking::STATUS_DRAFT,
                'package'                   => $package,
                'spots'                     => $spots,
                'amount_due'                => $amountDue,
                'discount_applied'          => $discountAmount,
                'accommodation_fee_applied' => $accommodationFee,
            ]);
        });
    }

    /**
     * Transition draft → pending_payment.
     */
    public function submitForPayment(Booking $booking): Booking
    {
        $this->assertStatus($booking, Booking::STATUS_DRAFT);

        $booking->update(['status' => Booking::STATUS_PENDING_PAYMENT]);

        $this->notify($booking->member, 'payment_pending', 'Booking Awaiting Payment', [
            'body'    => "Your booking {$booking->booking_ref} is awaiting payment of R{$booking->amount_due}.",
            'data'    => ['booking_ref' => $booking->booking_ref],
        ]);

        return $booking->refresh();
    }

    /**
     * Transition pending_payment → payment_uploaded (member uploads proof of payment).
     */
    public function uploadPayment(Booking $booking, UploadedFile $file, string $reference = null, string $method = 'eft'): Booking
    {
        $this->assertStatus($booking, Booking::STATUS_PENDING_PAYMENT);

        return DB::transaction(function () use ($booking, $file, $reference, $method) {
            $path = $file->store("payments/{$booking->booking_ref}", 'local');

            Payment::create([
                'booking_id' => $booking->id,
                'member_id'  => $booking->member_id,
                'amount'     => $booking->amount_due,
                'method'     => $method,
                'status'     => 'uploaded',
                'proof_path' => $path,
                'reference'  => $reference,
            ]);

            $booking->update(['status' => Booking::STATUS_PAYMENT_UPLOADED]);

            $this->notify($booking->member, 'payment_uploaded', 'Proof of Payment Received', [
                'body' => "We received your payment proof for {$booking->booking_ref}. We'll verify shortly.",
                'data' => ['booking_ref' => $booking->booking_ref],
            ]);

            return $booking->refresh();
        });
    }

    /**
     * Transition payment_uploaded → payment_verified (admin verifies).
     */
    public function verifyPayment(Booking $booking, int $verifiedByUserId): Booking
    {
        $this->assertStatus($booking, Booking::STATUS_PAYMENT_UPLOADED);

        return DB::transaction(function () use ($booking, $verifiedByUserId) {
            $booking->payments()
                ->where('status', 'uploaded')
                ->update([
                    'status'      => 'verified',
                    'verified_by' => $verifiedByUserId,
                    'verified_at' => now(),
                ]);

            $booking->update([
                'status'     => Booking::STATUS_PAYMENT_VERIFIED,
                'amount_paid' => $booking->amount_due,
            ]);

            return $booking->refresh();
        });
    }

    /**
     * Transition payment_verified → confirmed.
     */
    public function confirm(Booking $booking): Booking
    {
        $this->assertStatus($booking, Booking::STATUS_PAYMENT_VERIFIED);

        $booking->update([
            'status'       => Booking::STATUS_CONFIRMED,
            'confirmed_at' => now(),
        ]);

        $this->notify($booking->member, 'booking_confirmed', 'Booking Confirmed!', [
            'body' => "Your spot on {$booking->hike->title} is confirmed. See you on {$booking->hike->departs_at->format('D, d M Y')}!",
            'data' => ['booking_ref' => $booking->booking_ref, 'hike_id' => $booking->hike_id],
        ]);

        $email = $booking->member->user?->email;
        if ($email) {
            Mail::to($email)->send(new BookingConfirmedMail($booking));
        }

        return $booking->refresh();
    }

    /**
     * Transition confirmed → attended and award points.
     */
    public function markAttended(Booking $booking): Booking
    {
        $this->assertStatus($booking, Booking::STATUS_CONFIRMED);

        return DB::transaction(function () use ($booking) {
            $booking->update([
                'status'      => Booking::STATUS_ATTENDED,
                'attended_at' => now(),
            ]);

            $this->pointsService->credit(
                $booking->member,
                $booking->hike->points_awarded,
                'hike_attended',
                $booking
            );

            $member = $booking->member->refresh();
            $member->increment('hikes_attended');
            $member->refresh();

            $this->badgeService->checkAndAward($member);

            return $booking->refresh();
        });
    }

    /**
     * Cancel a booking (from any non-terminal state).
     */
    public function cancel(Booking $booking, string $reason = null): Booking
    {
        $terminal = [Booking::STATUS_ATTENDED, Booking::STATUS_CANCELLED, Booking::STATUS_REFUNDED];
        if (in_array($booking->status, $terminal)) {
            throw new \LogicException("Cannot cancel a booking in status [{$booking->status}].");
        }

        $booking->update([
            'status'       => Booking::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'notes'        => $reason ? ($booking->notes . "\n[Cancelled] " . $reason) : $booking->notes,
        ]);

        $this->notify($booking->member, 'booking_cancelled', 'Booking Cancelled', [
            'body' => "Your booking {$booking->booking_ref} has been cancelled.",
            'data' => ['booking_ref' => $booking->booking_ref],
        ]);

        return $booking->refresh();
    }

    /**
     * Reject uploaded payment and revert to pending_payment.
     */
    public function rejectPayment(Booking $booking, string $reason = null): Booking
    {
        $this->assertStatus($booking, Booking::STATUS_PAYMENT_UPLOADED);

        return DB::transaction(function () use ($booking, $reason) {
            $booking->payments()
                ->where('status', 'uploaded')
                ->update(['status' => 'rejected']);

            $booking->update(['status' => Booking::STATUS_PENDING_PAYMENT]);

            $this->notify($booking->member, 'payment_rejected', 'Payment Proof Rejected', [
                'body' => "Your payment proof for {$booking->booking_ref} was rejected. " . ($reason ?? 'Please re-upload.'),
                'data' => ['booking_ref' => $booking->booking_ref],
            ]);

            $email = $booking->member->user?->email;
            if ($email) {
                Mail::to($email)->send(new PaymentRejectedMail($booking, $reason ?? 'Please re-upload a clear proof of payment.'));
            }

            return $booking->refresh();
        });
    }

    private function assertStatus(Booking $booking, string $expected): void
    {
        if ($booking->status !== $expected) {
            throw new \LogicException(
                "Expected booking status [{$expected}] but found [{$booking->status}]."
            );
        }
    }

    private function notify(Member $member, string $type, string $title, array $params): void
    {
        MemberNotification::create([
            'member_id' => $member->id,
            'type'      => $type,
            'title'     => $title,
            'body'      => $params['body'] ?? '',
            'data'      => $params['data'] ?? null,
        ]);
    }
}
