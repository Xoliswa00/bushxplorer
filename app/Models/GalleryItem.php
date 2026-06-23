<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'type',
        'file_size',
        'mime_type',
        'duration_seconds',
        'is_featured',
        'is_approved',
        'sort_order',
        'social_platforms',
        'social_published_at',
    ];

    protected $casts = [
        'is_featured'         => 'boolean',
        'is_approved'         => 'boolean',
        'social_platforms'    => 'array',
        'social_published_at' => 'datetime',
    ];

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('is_approved', true);
    }

    public function scopePendingApproval(Builder $q): Builder
    {
        return $q->where('is_approved', false);
    }

    public function scopePhotos(Builder $q): Builder
    {
        return $q->where('type', 'photo');
    }

    public function scopeVideos(Builder $q): Builder
    {
        return $q->where('type', 'video');
    }

    public function scopePublishedToSocial(Builder $q): Builder
    {
        return $q->whereNotNull('social_published_at');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    public function isPublishedTo(string $platform): bool
    {
        return isset($this->social_platforms[$platform]['published_at']);
    }

    public function recordSocialPublish(string $platform, string $postId): void
    {
        $platforms = $this->social_platforms ?? [];
        $platforms[$platform] = [
            'post_id'      => $postId,
            'published_at' => now()->toISOString(),
        ];

        $this->update([
            'social_platforms'    => $platforms,
            'social_published_at' => $this->social_published_at ?? now(),
        ]);
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function hike(): BelongsTo
    {
        return $this->belongsTo(Hike::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function tags(): HasMany
    {
        return $this->hasMany(GalleryTag::class, 'gallery_item_id');
    }

    public function approvedTags(): HasMany
    {
        return $this->hasMany(GalleryTag::class, 'gallery_item_id')->where('approved', true);
    }
}
