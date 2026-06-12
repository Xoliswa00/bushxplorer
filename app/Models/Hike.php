<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Hike extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'description', 'location', 'trail_name', 'difficulty',
        'distance_km', 'elevation_gain_m', 'departs_at', 'returns_at', 'meeting_point',
        'price', 'max_capacity', 'min_capacity', 'is_published', 'is_cancelled',
        'points_awarded', 'cover_image',
        'includes_transport', 'transport_fee',
        'nights', 'accommodation_name', 'accommodation_cost_per_person',
        'what_is_included', 'what_to_bring',
    ];

    protected $casts = [
        'departs_at'   => 'datetime',
        'returns_at'   => 'datetime',
        'is_published'       => 'boolean',
        'is_cancelled'       => 'boolean',
        'includes_transport' => 'boolean',
        'price'              => 'decimal:2',
        'transport_fee'      => 'decimal:2',
        'accommodation_cost_per_person' => 'decimal:2',
        'distance_km'        => 'decimal:2',
    ];

    public function getIsOvernightAttribute(): bool
    {
        return ($this->nights ?? 0) > 0;
    }

    public function getTotalAccommodationCostAttribute(): float
    {
        return (float) $this->accommodation_cost_per_person * max(1, $this->nights ?? 0);
    }

    protected static function booted(): void
    {
        static::creating(function (Hike $hike) {
            if (empty($hike->slug)) {
                $hike->slug = Str::slug($hike->title);
            }
        });
    }

    public function getSpotsRemainingAttribute(): int
    {
        $taken = $this->bookings()
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('spots');
        return max(0, $this->max_capacity - $taken);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(GalleryItem::class);
    }

    public function waitlist(): HasMany
    {
        return $this->hasMany(Waitlist::class)->orderBy('position');
    }

    public function pickupPoints(): HasMany
    {
        return $this->hasMany(PickupPoint::class)->orderBy('sort_order');
    }
}
