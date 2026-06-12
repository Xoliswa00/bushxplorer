<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_DRAFT            = 'draft';
    const STATUS_PENDING_PAYMENT  = 'pending_payment';
    const STATUS_PAYMENT_UPLOADED = 'payment_uploaded';
    const STATUS_PAYMENT_VERIFIED = 'payment_verified';
    const STATUS_CONFIRMED        = 'confirmed';
    const STATUS_ATTENDED         = 'attended';
    const STATUS_CANCELLED        = 'cancelled';
    const STATUS_REFUNDED         = 'refunded';

    protected $fillable = [
        'booking_ref',
        'member_id',
        'hike_id',
        'pickup_point_id',
        'status',
        'spots',
        'amount_due',
        'amount_paid',
        'discount_applied',
        'transport_fee_applied',
        'notes',
        'confirmed_at',
        'attended_at',
        'cancelled_at',
    ];

    protected $casts = [
        'amount_due'            => 'decimal:2',
        'amount_paid'           => 'decimal:2',
        'discount_applied'      => 'decimal:2',
        'transport_fee_applied' => 'decimal:2',
        'confirmed_at'     => 'datetime',
        'attended_at'      => 'datetime',
        'cancelled_at'     => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_ref)) {
                $year = date('Y');
                $last = self::withTrashed()
                    ->whereYear('created_at', $year)
                    ->lockForUpdate()
                    ->count();
                $booking->booking_ref = "BXB-{$year}-" . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function hike(): BelongsTo
    {
        return $this->belongsTo(Hike::class);
    }

    public function pickupPoint(): BelongsTo
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
