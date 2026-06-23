<?php

namespace App\Services;

use App\Models\GalleryItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SocialPublishService
{
    /**
     * Publish to Instagram via Meta Graph API.
     * Requires: INSTAGRAM_ACCOUNT_ID, INSTAGRAM_ACCESS_TOKEN in .env
     * The file must be on a publicly reachable URL (use Storage::url with a CDN or ngrok in dev).
     */
    public function publishToInstagram(GalleryItem $item): string
    {
        $accountId   = config('services.instagram.account_id');
        $accessToken = config('services.instagram.access_token');
        $mediaUrl    = Storage::disk('public')->url($item->file_path);
        $caption     = $this->buildCaption($item);

        if ($item->type === 'video') {
            // Step 1 — create video container
            $container = Http::throw()->post(
                "https://graph.facebook.com/v19.0/{$accountId}/media",
                [
                    'media_type'   => 'REELS',
                    'video_url'    => $mediaUrl,
                    'caption'      => $caption,
                    'access_token' => $accessToken,
                ]
            )->json();

            $this->waitForContainer($container['id'], $accessToken);
        } else {
            // Step 1 — create image container
            $container = Http::throw()->post(
                "https://graph.facebook.com/v19.0/{$accountId}/media",
                [
                    'image_url'    => $mediaUrl,
                    'caption'      => $caption,
                    'access_token' => $accessToken,
                ]
            )->json();
        }

        // Step 2 — publish
        $result = Http::throw()->post(
            "https://graph.facebook.com/v19.0/{$accountId}/media_publish",
            [
                'creation_id'  => $container['id'],
                'access_token' => $accessToken,
            ]
        )->json();

        $postId = $result['id'];
        $item->recordSocialPublish('instagram', $postId);

        Log::info("GalleryItem {$item->id} published to Instagram as {$postId}");

        return $postId;
    }

    /**
     * Publish to a Facebook Page via Meta Graph API.
     * Requires: FACEBOOK_PAGE_ID, FACEBOOK_PAGE_ACCESS_TOKEN in .env
     */
    public function publishToFacebook(GalleryItem $item): string
    {
        $pageId      = config('services.facebook.page_id');
        $accessToken = config('services.facebook.page_access_token');
        $mediaUrl    = Storage::disk('public')->url($item->file_path);
        $caption     = $this->buildCaption($item);

        if ($item->type === 'video') {
            $result = Http::throw()->post(
                "https://graph.facebook.com/v19.0/{$pageId}/videos",
                [
                    'file_url'     => $mediaUrl,
                    'description'  => $caption,
                    'access_token' => $accessToken,
                ]
            )->json();
        } else {
            $result = Http::throw()->post(
                "https://graph.facebook.com/v19.0/{$pageId}/photos",
                [
                    'url'          => $mediaUrl,
                    'message'      => $caption,
                    'access_token' => $accessToken,
                ]
            )->json();
        }

        $postId = $result['id'];
        $item->recordSocialPublish('facebook', $postId);

        Log::info("GalleryItem {$item->id} published to Facebook as {$postId}");

        return $postId;
    }

    /**
     * Poll until Instagram's container is ready (videos can take a few seconds).
     */
    private function waitForContainer(string $containerId, string $accessToken, int $maxAttempts = 10): void
    {
        for ($i = 0; $i < $maxAttempts; $i++) {
            sleep(3);
            $status = Http::get("https://graph.facebook.com/v19.0/{$containerId}", [
                'fields'       => 'status_code',
                'access_token' => $accessToken,
            ])->json();

            if (($status['status_code'] ?? '') === 'FINISHED') {
                return;
            }

            if (($status['status_code'] ?? '') === 'ERROR') {
                throw new \RuntimeException("Instagram container processing failed for {$containerId}.");
            }
        }

        throw new \RuntimeException("Instagram container {$containerId} did not finish in time.");
    }

    private function buildCaption(GalleryItem $item): string
    {
        $parts = [];

        if ($item->caption) {
            $parts[] = $item->caption;
        }

        if ($item->hike) {
            $parts[] = "📍 {$item->hike->location}";
        }

        // Tag members who are in this photo
        $taggedNames = $item->approvedTags->map(fn ($t) => $t->member->full_name)->filter()->values();
        if ($taggedNames->isNotEmpty()) {
            $parts[] = 'With: ' . $taggedNames->join(', ');
        }

        $parts[] = '#BushXplorer #Hiking #NatureWalk #Outdoors';

        return implode("\n\n", $parts);
    }
}
