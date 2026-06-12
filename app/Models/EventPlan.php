<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by', 'title', 'type', 'tagline', 'description', 'concept_notes', 'cover_color',
        'location', 'trail_name', 'difficulty', 'departs_at', 'returns_at', 'meeting_point',
        'max_capacity', 'min_capacity', 'points_awarded', 'includes_transport',
        'transport_fee', 'transport_pickup_points',
        'expenses', 'target_margin_pct', 'price',
        'current_step', 'status', 'published_hike_id', 'notes',
    ];

    protected $casts = [
        'departs_at'              => 'datetime',
        'returns_at'              => 'datetime',
        'includes_transport'      => 'boolean',
        'transport_fee'           => 'decimal:2',
        'transport_pickup_points' => 'array',
        'expenses'                => 'array',
        'price'                   => 'decimal:2',
        'target_margin_pct'       => 'decimal:2',
    ];

    // ── Financials helpers ───────────────────────────────────────────────────

    public function totalExpenses(): float
    {
        return collect($this->expenses ?? [])->sum('amount');
    }

    public function breakEvenPrice(): float
    {
        $cap = max(1, $this->min_capacity ?? 1);
        $transport = $this->includes_transport ? (float) $this->transport_fee : 0;
        return round(($this->totalExpenses() / $cap) + $transport, 2);
    }

    public function suggestedPrice(): float
    {
        $breakEven = $this->breakEvenPrice();
        $margin    = (float) ($this->target_margin_pct ?? 20);
        return round($breakEven * (1 + $margin / 100), 2);
    }

    public function revenueAt(int $headcount): float
    {
        $ticketRevenue    = (float) ($this->price ?? $this->suggestedPrice()) * $headcount;
        $transportRevenue = $this->includes_transport ? (float) $this->transport_fee * $headcount : 0;
        return round($ticketRevenue + $transportRevenue - $this->totalExpenses(), 2);
    }

    public function revenueForecast(): array
    {
        $min      = max(1, (int) $this->min_capacity);
        $max      = max($min, (int) $this->max_capacity);
        $expected = (int) round($min + ($max - $min) * 0.75);

        return [
            'min'      => ['headcount' => $min,      'profit' => $this->revenueAt($min)],
            'expected' => ['headcount' => $expected,  'profit' => $this->revenueAt($expected)],
            'max'      => ['headcount' => $max,       'profit' => $this->revenueAt($max)],
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeForUser(Builder $q, int $userId): Builder
    {
        return $q->where('created_by', $userId);
    }

    public function scopeInProgress(Builder $q): Builder
    {
        return $q->whereNotIn('status', ['published']);
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function publishedHike(): BelongsTo
    {
        return $this->belongsTo(Hike::class, 'published_hike_id');
    }
}
