<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExplorerLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'min_points',
        'max_points',
        'discount_percentage',
        'badge_color',
        'perks',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public static function forPoints(int $points): ?self
    {
        return self::where('min_points', '<=', $points)
            ->where(function ($q) use ($points) {
                $q->whereNull('max_points')->orWhere('max_points', '>=', $points);
            })
            ->orderByDesc('min_points')
            ->first();
    }
}
