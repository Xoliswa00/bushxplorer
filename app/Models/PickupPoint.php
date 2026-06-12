<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PickupPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'hike_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'departure_time',
        'max_seats',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'latitude'       => 'decimal:7',
        'longitude'      => 'decimal:7',
        'departure_time' => 'datetime:H:i',
    ];

    public function getSeatsRemainingAttribute(): int
    {
        $taken = $this->bookings()
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->sum('spots');
        return max(0, $this->max_seats - $taken);
    }

    public function hike(): BelongsTo
    {
        return $this->belongsTo(Hike::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
