<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_ref',
        'member_id',
        'total',
        'status',
        'notes',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_ref)) {
                $year = date('Y');
                $last = self::withTrashed()
                    ->whereYear('created_at', $year)
                    ->lockForUpdate()
                    ->count();
                $order->order_ref = "BXO-{$year}-" . str_pad($last + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
