<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'criteria_type',
        'criteria_threshold',
        'color',
        'sort_order',
    ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_badge')
            ->withPivot('awarded_at')
            ->withTimestamps();
    }
}
