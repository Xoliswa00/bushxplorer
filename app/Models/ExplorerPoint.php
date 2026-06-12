<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ExplorerPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'points',
        'type',
        'reason',
        'pointable_id',
        'pointable_type',
        'running_balance',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function pointable(): MorphTo
    {
        return $this->morphTo();
    }
}
