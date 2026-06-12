<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Waitlist extends Model
{
    use HasFactory;

    protected $table = 'waitlist';

    protected $fillable = [
        'hike_id',
        'member_id',
        'position',
        'notified',
        'notified_at',
    ];

    protected $casts = [
        'notified'    => 'boolean',
        'notified_at' => 'datetime',
    ];

    public function hike(): BelongsTo
    {
        return $this->belongsTo(Hike::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
