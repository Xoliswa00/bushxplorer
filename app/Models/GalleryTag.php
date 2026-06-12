<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_item_id',
        'member_id',
        'tagged_by',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function galleryItem(): BelongsTo
    {
        return $this->belongsTo(GalleryItem::class, 'gallery_item_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
