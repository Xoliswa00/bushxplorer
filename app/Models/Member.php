<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_ref',
        'user_id',
        'explorer_level_id',
        'first_name',
        'last_name',
        'phone',
        'date_of_birth',
        'emergency_contact_name',
        'emergency_contact_phone',
        'total_points',
        'hikes_attended',
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_active'     => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Member $member) {
            if (empty($member->member_ref)) {
                $last = self::withTrashed()->lockForUpdate()->count();
                $member->member_ref = 'BX-' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function explorerLevel(): BelongsTo
    {
        return $this->belongsTo(ExplorerLevel::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function points(): HasMany
    {
        return $this->hasMany(ExplorerPoint::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function waitlistEntries(): HasMany
    {
        return $this->hasMany(Waitlist::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(MemberNotification::class);
    }
}
