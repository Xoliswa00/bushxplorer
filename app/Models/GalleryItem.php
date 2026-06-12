<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GalleryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gallery';

    protected $fillable = [
        'hike_id',
        'uploaded_by',
        'file_path',
        'thumbnail_path',
        'caption',
        'is_featured',
        'is_approved',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function hike(): BelongsTo
    {
        return $this->belongsTo(Hike::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
