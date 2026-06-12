<?php

namespace App\Livewire\Gallery;

use App\Models\GalleryItem;
use App\Services\GalleryService;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class GalleryManager extends Component
{
    use WithPagination;

    public string $tab       = 'pending';   // pending | approved | published
    public array  $platforms = [];          // selected platforms for batch publish
    public ?int   $publishId = null;

    #[Computed]
    public function items()
    {
        return match ($this->tab) {
            'pending'   => GalleryItem::with(['hike', 'uploader'])->pendingApproval()->latest()->paginate(20),
            'approved'  => GalleryItem::with(['hike', 'uploader'])->approved()->whereNull('social_published_at')->latest()->paginate(20),
            'published' => GalleryItem::with(['hike'])->publishedToSocial()->latest()->paginate(20),
            default     => collect(),
        };
    }

    public function approve(int $id): void
    {
        $item = GalleryItem::findOrFail($id);
        app(GalleryService::class)->approve($item);
        unset($this->items);
    }

    public function reject(int $id): void
    {
        $item = GalleryItem::findOrFail($id);
        app(GalleryService::class)->reject($item);
        unset($this->items);
    }

    public function openPublishModal(int $id): void
    {
        $this->publishId = $id;
        $this->platforms = [];
    }

    public function closePublishModal(): void
    {
        $this->publishId = null;
        $this->platforms = [];
    }

    public function publish(): void
    {
        if (! $this->publishId || empty($this->platforms)) return;

        $item = GalleryItem::findOrFail($this->publishId);

        $this->validate([
            'platforms'   => ['required', 'array', 'min:1'],
            'platforms.*' => ['in:instagram,facebook'],
        ]);

        app(GalleryService::class)->publishToSocial($item, $this->platforms);

        $this->closePublishModal();
        unset($this->items);

        session()->flash('success', 'Queued for publishing. Posts will appear within a minute.');
    }

    public function updatedTab(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.gallery.gallery-manager');
    }
}
