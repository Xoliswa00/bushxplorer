<?php

namespace App\Jobs;

use App\Models\GalleryItem;
use App\Services\SocialPublishService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class PublishToSocialJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $backoff = 60; // seconds between retries

    public function __construct(
        public readonly GalleryItem $item,
        public readonly string      $platform,  // 'instagram' | 'facebook'
    ) {}

    public function handle(SocialPublishService $service): void
    {
        if ($this->item->isPublishedTo($this->platform)) {
            Log::info("GalleryItem {$this->item->id} already published to {$this->platform}, skipping.");
            return;
        }

        match ($this->platform) {
            'instagram' => $service->publishToInstagram($this->item),
            'facebook'  => $service->publishToFacebook($this->item),
            default     => throw new \InvalidArgumentException("Unknown platform [{$this->platform}]"),
        };
    }

    public function failed(Throwable $e): void
    {
        Log::error("PublishToSocialJob failed for GalleryItem {$this->item->id} on {$this->platform}: {$e->getMessage()}");
    }
}
