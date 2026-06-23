<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    protected $fillable = [
        'name',
        'type',
        'region',
        'location',
        'avg_cost_per_person',
        'max_guests',
        'amenities',
        'description',
        'website',
        'phone',
        'booking_contact',
        'group_notes',
        'is_active',
    ];

    protected $casts = [
        'amenities'          => 'array',
        'avg_cost_per_person'=> 'float',
        'is_active'          => 'boolean',
    ];

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Search by region or name against a free-text query.
     * Also fuzzy-matches against a trip location string.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $words = array_filter(explode(' ', preg_replace('/[^a-z0-9 ]/i', ' ', $term)));

        return $query->where(function (Builder $q) use ($words, $term) {
            $q->where('name',     'like', "%{$term}%")
              ->orWhere('region',   'like', "%{$term}%")
              ->orWhere('location', 'like', "%{$term}%");

            foreach ($words as $word) {
                if (strlen($word) >= 3) {
                    $q->orWhere('region',   'like', "%{$word}%")
                      ->orWhere('location', 'like', "%{$word}%")
                      ->orWhere('name',     'like', "%{$word}%");
                }
            }
        });
    }

    // ── Presentation helpers ─────────────────────────────────────────────────

    public function typeLabel(): string
    {
        return match ($this->type) {
            'lodge'        => 'Lodge',
            'camp'         => 'Campsite',
            'guesthouse'   => 'Guesthouse',
            'hostel'       => 'Hostel',
            'resort'       => 'Resort',
            'farm'         => 'Farm Stay',
            'backpackers'  => 'Backpackers',
            'chalet'       => 'Chalet',
            default        => ucfirst($this->type),
        };
    }

    public function typeColor(): string
    {
        return match ($this->type) {
            'lodge'        => '#166534',
            'camp'         => '#92400e',
            'guesthouse'   => '#1e40af',
            'hostel'       => '#6b21a8',
            'resort'       => '#0e7490',
            'farm'         => '#3f6212',
            'backpackers'  => '#9a3412',
            'chalet'       => '#1c4e80',
            default        => '#374151',
        };
    }
}
