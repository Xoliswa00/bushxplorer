<?php

namespace App\Livewire\Member;

use App\Models\Badge;
use App\Models\ExplorerLevel;
use App\Models\Member;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class MemberDashboard extends Component
{
    #[Computed]
    public function member(): Member
    {
        return Member::with(['explorerLevel', 'badges'])
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    #[Computed]
    public function upcomingBookings()
    {
        return $this->member->bookings()
            ->with('hike')
            ->whereHas('hike', fn ($q) => $q->where('departs_at', '>', now()))
            ->whereNotIn('status', ['cancelled', 'refunded'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function recentBookings()
    {
        return $this->member->bookings()
            ->with('hike')
            ->whereHas('hike', fn ($q) => $q->where('departs_at', '<', now()))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function nextLevel(): ?ExplorerLevel
    {
        return ExplorerLevel::where('min_points', '>', $this->member->total_points)
            ->orderBy('min_points')
            ->first();
    }

    #[Computed]
    public function levelProgress(): int
    {
        $current = $this->member->explorerLevel;
        $next    = $this->nextLevel;

        if (! $current || ! $next) return 100;

        $range  = $next->min_points - $current->min_points;
        $earned = $this->member->total_points - $current->min_points;

        return $range > 0 ? (int) min(100, round($earned / $range * 100)) : 100;
    }

    #[Computed]
    public function allBadges(): Collection
    {
        $earned = $this->member->badges->keyBy('id');

        return Badge::orderBy('sort_order')->get()->map(function (Badge $badge) use ($earned) {
            $badge->earned     = $earned->has($badge->id);
            $badge->awarded_at = $earned->get($badge->id)?->pivot?->awarded_at;
            return $badge;
        });
    }

    #[Computed]
    public function earnedBadgeCount(): int
    {
        return $this->member->badges->count();
    }

    #[Computed]
    public function recentNotifications(): Collection
    {
        return $this->member->notifications()->limit(5)->get();
    }

    public function render()
    {
        return view('livewire.member.member-dashboard');
    }
}
