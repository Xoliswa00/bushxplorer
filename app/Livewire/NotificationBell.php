<?php

namespace App\Livewire;

use App\Models\Member;
use App\Models\MemberNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool $open = false;

    #[Computed]
    public function member(): ?Member
    {
        if (! Auth::check()) {
            return null;
        }
        return Member::where('user_id', Auth::id())->first();
    }

    #[Computed]
    public function unreadCount(): int
    {
        return $this->member
            ? MemberNotification::where('member_id', $this->member->id)->unread()->count()
            : 0;
    }

    #[Computed]
    public function notifications()
    {
        if (! $this->member) {
            return collect();
        }

        return MemberNotification::where('member_id', $this->member->id)
            ->latest()
            ->take(15)
            ->get();
    }

    public function toggleOpen(): void
    {
        $this->open = ! $this->open;
    }

    public function markRead(int $id): void
    {
        $notification = MemberNotification::where('member_id', $this->member?->id)
            ->findOrFail($id);

        $notification->markAsRead();

        unset($this->notifications, $this->unreadCount);
    }

    public function markAllRead(): void
    {
        if ($this->member) {
            MemberNotification::markAllReadForMember($this->member->id);
        }

        unset($this->notifications, $this->unreadCount);
    }

    #[On('booking-created')]
    #[On('payment-uploaded')]
    public function refreshNotifications(): void
    {
        unset($this->notifications, $this->unreadCount);
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
