<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\Member;
use App\Models\MemberNotification;

class BadgeService
{
    public function checkAndAward(Member $member): array
    {
        $earnedIds = $member->badges()->pluck('badge_id')->toArray();

        $overnightCount = $member->bookings()
            ->where('status', 'attended')
            ->whereHas('hike', fn ($q) => $q->where('nights', '>', 0))
            ->count();

        $stats = [
            'hikes_attended'    => (int) $member->hikes_attended,
            'total_points'      => (int) $member->total_points,
            'overnight_bookings' => $overnightCount,
        ];

        $newlyAwarded = [];

        Badge::whereNotIn('id', $earnedIds)
            ->orderBy('sort_order')
            ->each(function (Badge $badge) use ($member, $stats, &$newlyAwarded) {
                $value = $stats[$badge->criteria_type] ?? 0;
                if ($value >= $badge->criteria_threshold) {
                    $member->badges()->attach($badge->id, ['awarded_at' => now()]);
                    $newlyAwarded[] = $badge;

                    MemberNotification::create([
                        'member_id' => $member->id,
                        'type'      => 'badge_awarded',
                        'title'     => "Badge Unlocked: {$badge->name}",
                        'body'      => "{$badge->icon} {$badge->description}",
                        'data'      => ['badge_slug' => $badge->slug],
                    ]);
                }
            });

        return $newlyAwarded;
    }
}
