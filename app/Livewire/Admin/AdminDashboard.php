<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use App\Models\ExplorerLevel;
use App\Models\Hike;
use App\Models\Member;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class AdminDashboard extends Component
{
    // ── KPI Cards ─────────────────────────────────────────────────────────────

    #[Computed]
    public function revenueThisYear(): float
    {
        return (float) Booking::whereIn('status', ['payment_verified', 'confirmed', 'attended'])
            ->whereYear('created_at', now()->year)
            ->sum('amount_due');
    }

    #[Computed]
    public function revenueLastMonth(): float
    {
        return (float) Booking::whereIn('status', ['payment_verified', 'confirmed', 'attended'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('amount_due');
    }

    #[Computed]
    public function activeMemberCount(): int
    {
        return Member::where('is_active', true)->count();
    }

    #[Computed]
    public function newMembersThisMonth(): int
    {
        return Member::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    #[Computed]
    public function pendingVerificationCount(): int
    {
        return Booking::where('status', Booking::STATUS_PAYMENT_UPLOADED)->count();
    }

    #[Computed]
    public function upcomingTripCount(): int
    {
        return Hike::where('is_published', true)
            ->where('is_cancelled', false)
            ->where('departs_at', '>', now())
            ->where('departs_at', '<=', now()->addDays(90))
            ->count();
    }

    // ── Action Queue ──────────────────────────────────────────────────────────

    #[Computed]
    public function pendingVerifications(): Collection
    {
        return Booking::with(['member.explorerLevel', 'hike'])
            ->where('status', Booking::STATUS_PAYMENT_UPLOADED)
            ->orderBy('updated_at')
            ->limit(10)
            ->get();
    }

    // ── Upcoming Trips ────────────────────────────────────────────────────────

    #[Computed]
    public function upcomingHikes(): Collection
    {
        return Hike::withCount([
            'bookings as confirmed_spots' => fn ($q) =>
                $q->whereIn('status', ['confirmed', 'attended', 'payment_verified', 'payment_uploaded']),
        ])
            ->where('is_published', true)
            ->where('is_cancelled', false)
            ->where('departs_at', '>', now())
            ->orderBy('departs_at')
            ->limit(8)
            ->get();
    }

    // ── Member Level Distribution ─────────────────────────────────────────────

    #[Computed]
    public function levelDistribution(): Collection
    {
        $levels = ExplorerLevel::orderBy('min_points')->get();
        $total  = max(1, Member::where('is_active', true)->count());

        return $levels->map(function (ExplorerLevel $level) use ($total) {
            $count = Member::where('explorer_level_id', $level->id)->where('is_active', true)->count();
            return [
                'name'    => $level->name,
                'color'   => $level->badge_color ?? '#166534',
                'count'   => $count,
                'percent' => round($count / $total * 100),
            ];
        });
    }

    // ── Recent Activity ───────────────────────────────────────────────────────

    #[Computed]
    public function recentBookings(): Collection
    {
        return Booking::with(['member', 'hike'])
            ->whereIn('status', [
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_PAYMENT_UPLOADED,
                Booking::STATUS_PAYMENT_VERIFIED,
            ])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard');
    }
}
