<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Member;
use App\Services\BadgeService;
use Illuminate\Database\Seeder;

class BadgesSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Attendance milestones
            [
                'slug'                => 'first-steps',
                'name'                => 'First Steps',
                'icon'                => '🥾',
                'description'         => 'Attended your very first adventure',
                'criteria_type'       => 'hikes_attended',
                'criteria_threshold'  => 1,
                'color'               => '#4a7c59',
                'sort_order'          => 10,
            ],
            [
                'slug'                => 'trail-blazer',
                'name'                => 'Trail Blazer',
                'icon'                => '🔥',
                'description'         => 'Completed 5 adventures',
                'criteria_type'       => 'hikes_attended',
                'criteria_threshold'  => 5,
                'color'               => '#e07b39',
                'sort_order'          => 20,
            ],
            [
                'slug'                => 'pathfinder',
                'name'                => 'Pathfinder',
                'icon'                => '🧭',
                'description'         => 'Explored 10 different trails',
                'criteria_type'       => 'hikes_attended',
                'criteria_threshold'  => 10,
                'color'               => '#1d6b4e',
                'sort_order'          => 30,
            ],
            [
                'slug'                => 'summit-master',
                'name'                => 'Summit Master',
                'icon'                => '🏔️',
                'description'         => 'Conquered 20 expeditions',
                'criteria_type'       => 'hikes_attended',
                'criteria_threshold'  => 20,
                'color'               => '#7c3aed',
                'sort_order'          => 40,
            ],
            [
                'slug'                => 'expedition-leader',
                'name'                => 'Expedition Leader',
                'icon'                => '🦅',
                'description'         => 'Led the pack across 50 adventures',
                'criteria_type'       => 'hikes_attended',
                'criteria_threshold'  => 50,
                'color'               => '#c9a84c',
                'sort_order'          => 50,
            ],

            // Points milestones
            [
                'slug'                => 'point-collector',
                'name'                => 'Point Collector',
                'icon'                => '⭐',
                'description'         => 'Earned 100 Explorer Points',
                'criteria_type'       => 'total_points',
                'criteria_threshold'  => 100,
                'color'               => '#c9a84c',
                'sort_order'          => 60,
            ],
            [
                'slug'                => 'adventure-veteran',
                'name'                => 'Adventure Veteran',
                'icon'                => '💎',
                'description'         => 'Earned 500 Explorer Points',
                'criteria_type'       => 'total_points',
                'criteria_threshold'  => 500,
                'color'               => '#2563eb',
                'sort_order'          => 70,
            ],
            [
                'slug'                => 'highland-legend',
                'name'                => 'Highland Legend',
                'icon'                => '🏆',
                'description'         => 'Earned 1 000 Explorer Points',
                'criteria_type'       => 'total_points',
                'criteria_threshold'  => 1000,
                'color'               => '#92400e',
                'sort_order'          => 80,
            ],

            // Overnight achievements
            [
                'slug'                => 'overnight-adventurer',
                'name'                => 'Overnight Adventurer',
                'icon'                => '🌙',
                'description'         => 'Slept under the stars on an overnight trip',
                'criteria_type'       => 'overnight_bookings',
                'criteria_threshold'  => 1,
                'color'               => '#4c1d95',
                'sort_order'          => 90,
            ],
            [
                'slug'                => 'wilderness-sleeper',
                'name'                => 'Wilderness Sleeper',
                'icon'                => '⛺',
                'description'         => 'Completed 3 overnight expeditions',
                'criteria_type'       => 'overnight_bookings',
                'criteria_threshold'  => 3,
                'color'               => '#1e3a5f',
                'sort_order'          => 100,
            ],
        ];

        foreach ($badges as $data) {
            Badge::updateOrCreate(['slug' => $data['slug']], $data);
        }

        $this->command->info('Badges seeded. Retroactively awarding badges to existing members...');

        $service = app(BadgeService::class);
        $awarded = 0;

        Member::with(['badges', 'bookings.hike'])->each(function (Member $member) use ($service, &$awarded) {
            $new = $service->checkAndAward($member);
            $awarded += count($new);
        });

        $this->command->info("Awarded {$awarded} badge(s) to existing members.");
    }
}
