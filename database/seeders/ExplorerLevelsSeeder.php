<?php

namespace Database\Seeders;

use App\Models\ExplorerLevel;
use Illuminate\Database\Seeder;

class ExplorerLevelsSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name'                => 'Bronze',
                'min_points'          => 0,
                'max_points'          => 99,
                'discount_percentage' => 0.00,
                'badge_color'         => '#CD7F32',
                'perks'               => 'Access to all standard hikes.',
            ],
            [
                'name'                => 'Silver',
                'min_points'          => 100,
                'max_points'          => 299,
                'discount_percentage' => 5.00,
                'badge_color'         => '#C0C0C0',
                'perks'               => '5% discount on all hikes. Priority waitlist.',
            ],
            [
                'name'                => 'Gold',
                'min_points'          => 300,
                'max_points'          => 699,
                'discount_percentage' => 10.00,
                'badge_color'         => '#FFD700',
                'perks'               => '10% discount. Early booking access. Free merchandise item annually.',
            ],
            [
                'name'                => 'Platinum',
                'min_points'          => 700,
                'max_points'          => null,
                'discount_percentage' => 15.00,
                'badge_color'         => '#E5E4E2',
                'perks'               => '15% discount. VIP hike access. Complimentary T-shirt annually. Dedicated guide.',
            ],
        ];

        foreach ($levels as $level) {
            ExplorerLevel::updateOrCreate(['name' => $level['name']], $level);
        }
    }
}
