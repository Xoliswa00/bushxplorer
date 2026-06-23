<?php

namespace App\Services;

use App\Jobs\PublishToSocialJob;
use App\Models\GalleryItem;
use App\Models\GalleryTag;
use App\Models\Hike;
use App\Models\Member;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class GalleryService
{
    /**
     * Store an uploaded photo or video, generate thumbnail for photos.
     */
    public function store(UploadedFile $file, Hike $hike, Member $uploader, string $caption = null): GalleryItem
    {
        $type     = str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'photo';
        $dir      = "gallery/hike-{$hike->id}";
        $path     = $file->store($dir, 'public');
        $thumbPath = null;

        if ($type === 'photo') {
            $thumbPath = $this->generateThumbnail($file, $dir, $path);
        }

        return GalleryItem::create([
            'hike_id'       => $hike->id,
            'uploaded_by'   => $uploader->user_id,
            'file_path'     => $path,
            'thumbnail_path'=> $thumbPath,
            'caption'       => $caption,
            'type'          => $type,
            'file_size'     => $file->getSize(),
            'mime_type'     => $file->getMimeType(),
            'is_approved'   => false,
            'is_featured'   => false,
        ]);
    }

    /**
     * Admin approves an item, making it visible in the gallery.
     */
    public function approve(GalleryItem $item): GalleryItem
    {
        $item->update(['is_approved' => true]);
        return $item->refresh();
    }

    /**
     * Admin rejects (soft-deletes) an inappropriate upload.
     */
    public function reject(GalleryItem $item): void
    {
        $item->delete();
    }

    /**
     * Tag a member in a gallery item (self-tag or admin-tag).
     * Only valid if the member attended the same hike.
     */
    public function tagMember(GalleryItem $item, Member $member, string $taggedBy = 'self'): GalleryTag
    {
        $attended = $member->bookings()
            ->where('hike_id', $item->hike_id)
            ->where('status', 'attended')
            ->exists();

        if (! $attended) {
            throw new \LogicException("Member {$member->member_ref} did not attend this hike.");
        }

        return GalleryTag::firstOrCreate(
            ['gallery_item_id' => $item->id, 'member_id' => $member->id],
            ['tagged_by' => $taggedBy, 'approved' => $taggedBy === 'admin']
        );
    }

    /**
     * Remove a tag (member untags themselves or admin removes).
     */
    public function removeTag(GalleryItem $item, Member $member): void
    {
        GalleryTag::where('gallery_item_id', $item->id)
            ->where('member_id', $member->id)
            ->delete();
    }

    /**
     * Dispatch social publishing jobs for the given platforms.
     *
     * @param  string[]  $platforms  e.g. ['instagram', 'facebook']
     */
    public function publishToSocial(GalleryItem $item, array $platforms): void
    {
        if (! $item->is_approved) {
            throw new \LogicException('Cannot publish an unapproved gallery item.');
        }

        foreach ($platforms as $platform) {
            PublishToSocialJob::dispatch($item, $platform);
        }
    }

    private function generateThumbnail(UploadedFile $file, string $dir, string $originalPath): string
    {
        $thumbName = 'thumb_' . basename($originalPath);
        $thumbPath = $dir . '/' . $thumbName;

        $image = Image::read($file->getRealPath())
            ->cover(400, 400);

        Storage::disk('public')->put($thumbPath, $image->toJpeg(80));

        return $thumbPath;
    }
}
