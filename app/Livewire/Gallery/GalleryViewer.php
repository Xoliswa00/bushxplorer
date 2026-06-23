<?php

namespace App\Livewire\Gallery;

use App\Models\GalleryItem;
use App\Models\Hike;
use App\Models\Member;
use App\Services\GalleryService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class GalleryViewer extends Component
{
    use WithPagination;

    public int     $hikeId;
    public string  $filter     = 'all';     // all | photos | videos
    public ?int    $lightboxId = null;

    public function mount(int $hikeId): void
    {
        $this->hikeId = $hikeId;
    }

    #[Computed]
    public function hike(): Hike
    {
        return Hike::findOrFail($this->hikeId);
    }

    #[Computed]
    public function member(): ?Member
    {
        if (! Auth::check()) return null;
        return Member::where('user_id', Auth::id())->first();
    }

    #[Computed]
    public function attended(): bool
    {
        return $this->member?->bookings()
            ->where('hike_id', $this->hikeId)
            ->where('status', 'attended')
            ->exists() ?? false;
    }

    #[Computed]
    public function items()
    {
        $q = GalleryItem::with(['tags.member'])
            ->where('hike_id', $this->hikeId)
            ->approved();

        if ($this->filter === 'photos') $q->photos();
        if ($this->filter === 'videos') $q->videos();

        return $q->orderByDesc('is_featured')->orderBy('sort_order')->paginate(24);
    }

    #[Computed]
    public function lightboxItem(): ?GalleryItem
    {
        return $this->lightboxId
            ? GalleryItem::with('approvedTags.member')->find($this->lightboxId)
            : null;
    }

    public function openLightbox(int $id): void
    {
        $this->lightboxId = $id;
    }

    public function closeLightbox(): void
    {
        $this->lightboxId = null;
    }

    public function tagMyself(int $itemId): void
    {
        if (! $this->member || ! $this->attended) return;

        $item = GalleryItem::findOrFail($itemId);

        try {
            app(GalleryService::class)->tagMember($item, $this->member, 'self');
            unset($this->items, $this->lightboxItem);
        } catch (\LogicException $e) {
            $this->addError('tag', $e->getMessage());
        }
    }

    public function untagMyself(int $itemId): void
    {
        if (! $this->member) return;

        $item = GalleryItem::findOrFail($itemId);
        app(GalleryService::class)->removeTag($item, $this->member);
        unset($this->items, $this->lightboxItem);
    }

    #[On('media-uploaded')]
    public function refreshGallery(): void
    {
        unset($this->items);
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.gallery.gallery-viewer');
    }
}
