<?php

namespace App\Livewire\Gallery;

use App\Models\Hike;
use App\Models\Member;
use App\Services\GalleryService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class GalleryUpload extends Component
{
    use WithFileUploads;

    public int    $hikeId;
    public string $caption = '';

    #[Rule(['required', 'file', 'mimes:jpg,jpeg,png,gif,mp4,mov,avi', 'max:102400'])]
    public $mediaFile = null;

    public bool $uploaded = false;

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
    public function member(): Member
    {
        return Member::where('user_id', Auth::id())->firstOrFail();
    }

    #[Computed]
    public function canUpload(): bool
    {
        return $this->member->bookings()
            ->where('hike_id', $this->hikeId)
            ->where('status', 'attended')
            ->exists();
    }

    public function upload(): void
    {
        if (! $this->canUpload) {
            $this->addError('mediaFile', 'Only members who attended this hike can upload media.');
            return;
        }

        $this->validate([
            'mediaFile' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,mp4,mov,avi', 'max:102400'],
            'caption'   => ['nullable', 'string', 'max:300'],
        ]);

        app(GalleryService::class)->store(
            $this->mediaFile,
            $this->hike,
            $this->member,
            $this->caption ?: null
        );

        $this->uploaded  = true;
        $this->mediaFile = null;
        $this->caption   = '';

        $this->dispatch('media-uploaded', hikeId: $this->hikeId);
    }

    public function render()
    {
        return view('livewire.gallery.gallery-upload');
    }
}
