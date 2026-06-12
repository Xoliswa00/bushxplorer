<?php

namespace App\Services;

use App\Models\ExplorerLevel;
use App\Models\ExplorerPoint;
use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExplorerPointsService
{
    /**
     * Credit points to a member (double-entry: records the entry + updates balance).
     */
    public function credit(Member $member, int $points, string $reason, Model $pointable): ExplorerPoint
    {
        return DB::transaction(function () use ($member, $points, $reason, $pointable) {
            $member->lockForUpdate()->find($member->id);

            $balance = $member->total_points + $points;

            $entry = ExplorerPoint::create([
                'member_id'       => $member->id,
                'points'          => $points,
                'type'            => 'credit',
                'reason'          => $reason,
                'pointable_id'    => $pointable->getKey(),
                'pointable_type'  => get_class($pointable),
                'running_balance' => $balance,
            ]);

            $member->update(['total_points' => $balance]);
            $this->recalculateLevel($member);

            return $entry;
        });
    }

    /**
     * Debit points from a member.
     */
    public function debit(Member $member, int $points, string $reason, Model $pointable): ExplorerPoint
    {
        return DB::transaction(function () use ($member, $points, $reason, $pointable) {
            $member->refresh();

            if ($member->total_points < $points) {
                throw new \LogicException("Insufficient points. Has {$member->total_points}, requires {$points}.");
            }

            $balance = $member->total_points - $points;

            $entry = ExplorerPoint::create([
                'member_id'       => $member->id,
                'points'          => -$points,
                'type'            => 'debit',
                'reason'          => $reason,
                'pointable_id'    => $pointable->getKey(),
                'pointable_type'  => get_class($pointable),
                'running_balance' => $balance,
            ]);

            $member->update(['total_points' => $balance]);
            $this->recalculateLevel($member);

            return $entry;
        });
    }

    /**
     * Return passport-style data: current level, points, history, progress to next level.
     */
    public function getPassportData(Member $member): array
    {
        $member->loadMissing('explorerLevel');

        $currentLevel = $member->explorerLevel;
        $nextLevel    = ExplorerLevel::where('min_points', '>', $member->total_points)
            ->orderBy('min_points')
            ->first();

        $progressPct = null;
        if ($currentLevel && $nextLevel) {
            $range       = $nextLevel->min_points - $currentLevel->min_points;
            $earned      = $member->total_points - $currentLevel->min_points;
            $progressPct = $range > 0 ? min(100, round(($earned / $range) * 100)) : 100;
        }

        $history = ExplorerPoint::where('member_id', $member->id)
            ->latest()
            ->take(20)
            ->get();

        return [
            'member_ref'      => $member->member_ref,
            'full_name'       => $member->full_name,
            'total_points'    => $member->total_points,
            'hikes_attended'  => $member->hikes_attended,
            'current_level'   => $currentLevel?->name,
            'badge_color'     => $currentLevel?->badge_color,
            'discount_pct'    => $currentLevel?->discount_percentage ?? 0,
            'next_level'      => $nextLevel?->name,
            'points_to_next'  => $nextLevel ? max(0, $nextLevel->min_points - $member->total_points) : 0,
            'progress_pct'    => $progressPct ?? 100,
            'recent_history'  => $history,
        ];
    }

    private function recalculateLevel(Member $member): void
    {
        $level = ExplorerLevel::forPoints($member->total_points);
        if ($level && $member->explorer_level_id !== $level->id) {
            $member->update(['explorer_level_id' => $level->id]);
        }
    }
}
