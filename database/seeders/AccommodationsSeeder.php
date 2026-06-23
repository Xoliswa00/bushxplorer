<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use Illuminate\Database\Seeder;

class AccommodationsSeeder extends Seeder
{
    public function run(): void
    {
        $spots = [

            // ── Magaliesberg / Hartbeespoort ──────────────────────────────────
            [
                'name'                => 'De Hoek Mountain Lodge',
                'type'                => 'lodge',
                'region'              => 'Magaliesberg',
                'location'            => 'Magaliesberg, North West',
                'avg_cost_per_person' => 680,
                'max_guests'          => 60,
                'amenities'           => ['braai', 'pool', 'hiking_trails', 'guided_hikes', 'wifi', 'breakfast_included'],
                'description'         => 'Boutique mountain lodge nestled in the Magaliesberg valley. Superb trails directly from the lodge, rock faces, and raptor sightings. Popular with Joburg hiking groups.',
                'phone'               => '014 577 1188',
                'group_notes'         => 'Offers group rates for 20+ people. Self-catering chalets available. Ask about the gorge trail package.',
            ],
            [
                'name'                => 'Magalies Mountain Lodge',
                'type'                => 'lodge',
                'region'              => 'Magaliesberg',
                'location'            => 'Hartbeespoort, North West',
                'avg_cost_per_person' => 520,
                'max_guests'          => 80,
                'amenities'           => ['braai', 'pool', 'wifi', 'restaurant', 'conference_room'],
                'description'         => 'Well-known conference and leisure lodge on the Magaliesberg ridge. Comfortable rooms, large lapa, close to Hartbeespoort Dam. Easy access from Sandton (1h).',
                'phone'               => '012 207 1081',
                'group_notes'         => 'Group packages from 15 pax. Breakfast and dinner packages available. 10% discount for groups booking 3+ months ahead.',
            ],
            [
                'name'                => 'Sparkling Waters Hotel & Spa',
                'type'                => 'resort',
                'region'              => 'Magaliesberg',
                'location'            => 'Schoemansville, Hartbeespoort',
                'avg_cost_per_person' => 850,
                'max_guests'          => 120,
                'amenities'           => ['spa', 'pool', 'wifi', 'restaurant', 'breakfast_included', 'braai'],
                'description'         => 'Upmarket resort on the banks of Hartbeespoort Dam. Spa, pools, and direct dam views. Ideal for groups wanting comfort after a tough trail day.',
                'phone'               => '012 253 0031',
                'group_notes'         => 'Hiking + spa packages popular with women\'s groups. Contact groups coordinator for bespoke packages.',
            ],
            [
                'name'                => 'Mount Amanzi Holiday Resort',
                'type'                => 'resort',
                'region'              => 'Hartbeespoort',
                'location'            => 'Hartbeespoort, North West',
                'avg_cost_per_person' => 380,
                'max_guests'          => 200,
                'amenities'           => ['braai', 'pool', 'camping', 'chalets', 'activities'],
                'description'         => 'Family resort with a mix of camping, self-catering chalets, and hotel rooms. Budget-friendly for large groups. Short drive to Magaliesberg trails.',
                'phone'               => '012 253 0052',
                'group_notes'         => 'Large camping area for budget groups. Chalets sleep 4–6. Ideal for mixed budget groups.',
            ],
            [
                'name'                => 'Magaliesberg Canopy Tour & Lodge',
                'type'                => 'lodge',
                'region'              => 'Magaliesberg',
                'location'            => 'Magaliesberg, North West',
                'avg_cost_per_person' => 600,
                'max_guests'          => 40,
                'amenities'           => ['braai', 'hiking_trails', 'guided_hikes', 'canopy_tour', 'breakfast_included'],
                'description'         => 'Eco-lodge adjacent to the Magaliesberg Canopy Tour ziplines. Rustic but well-equipped. Perfect blend of hiking and adventure activities.',
                'group_notes'         => 'Bundle hike + canopy tour for group discounts. Overnight packages include guided morning trail.',
            ],

            // ── Drakensberg ──────────────────────────────────────────────────
            [
                'name'                => 'Cathedral Peak Hotel',
                'type'                => 'lodge',
                'region'              => 'Drakensberg',
                'location'            => 'Cathedral Peak, KwaZulu-Natal',
                'avg_cost_per_person' => 1200,
                'max_guests'          => 140,
                'amenities'           => ['pool', 'restaurant', 'bar', 'hiking_trails', 'guided_hikes', 'wifi', 'breakfast_included'],
                'description'         => 'Iconic hotel at the foot of the Cathedral Peak massif. Direct access to some of SA\'s finest trails including the Mikes Pass and Cathedral Peak summit hike.',
                'phone'               => '036 488 1888',
                'group_notes'         => 'Guided hike packages for groups. Fully inclusive DBB option. Certified hiking guides on staff. UIAGM-qualified guide for technical routes.',
            ],
            [
                'name'                => 'Inkosana Lodge',
                'type'                => 'backpackers',
                'region'              => 'Drakensberg',
                'location'            => 'Champagne Valley, Central Drakensberg',
                'avg_cost_per_person' => 250,
                'max_guests'          => 50,
                'amenities'           => ['braai', 'communal_kitchen', 'hiking_trails', 'guided_hikes', 'dorm_rooms', 'private_rooms'],
                'description'         => 'Budget hikers\' haven in the heart of Champagne Valley. Route maps, trail advice, and pre-dawn breakfast for summit attempts. Hugely popular with hiking clubs.',
                'phone'               => '036 468 1202',
                'group_notes'         => 'Group dorm packages from R200/person. Free trail maps and gear storage. Breakfast-box service for early starts.',
            ],
            [
                'name'                => 'Amphitheatre Backpackers',
                'type'                => 'backpackers',
                'region'              => 'Drakensberg',
                'location'            => 'Royal Natal, Northern Drakensberg, KwaZulu-Natal',
                'avg_cost_per_person' => 220,
                'max_guests'          => 40,
                'amenities'           => ['braai', 'communal_kitchen', 'hiking_trails', 'guided_hikes', 'camping'],
                'description'         => 'Budget lodge and camping at the base of the Amphitheatre. Walking distance to the Tugela Falls trailhead. Very popular with Jozi hiking groups for the Sentinel hike.',
                'group_notes'         => 'Camping R150/person. Dorms from R200. Book early for peak season (Jun–Aug). Early checkout for Sentinel hikers.',
            ],
            [
                'name'                => 'Giants Castle Camp',
                'type'                => 'camp',
                'region'              => 'Drakensberg',
                'location'            => 'Giants Castle, Central Drakensberg, KwaZulu-Natal',
                'avg_cost_per_person' => 420,
                'max_guests'          => 80,
                'amenities'           => ['braai', 'hiking_trails', 'guided_hikes', 'restaurant', 'bird_hide'],
                'description'         => 'Ezemvelo KZN Wildlife-managed camp within the Giants Castle reserve. Lammergeier (bearded vulture) hide nearby. Excellent for multi-day Drakensberg traverses.',
                'phone'               => '033 845 1000',
                'group_notes'         => 'Book via Ezemvelo reservations. Group units (sleep 6–8) available. Conservation fees included in camp rate.',
            ],

            // ── Cederberg ────────────────────────────────────────────────────
            [
                'name'                => 'Algeria Campsite & Cottages',
                'type'                => 'camp',
                'region'              => 'Cederberg',
                'location'            => 'Cederberg Wilderness Area, Western Cape',
                'avg_cost_per_person' => 180,
                'max_guests'          => 100,
                'amenities'           => ['braai', 'camping', 'hiking_trails', 'guided_hikes', 'communal_ablutions'],
                'description'         => 'CapeNature-managed campsite in the heart of the Cederberg. Base camp for Wolfberg Arch, Maltese Cross, and multi-day Cederberg Trail.',
                'phone'               => '021 483 0190',
                'group_notes'         => 'Book via CapeNature reservations. Large group sites available. Permit required for overnight trails.',
            ],
            [
                'name'                => 'Cederberg Wilderness House',
                'type'                => 'guesthouse',
                'region'              => 'Cederberg',
                'location'            => 'Citrusdal, Western Cape',
                'avg_cost_per_person' => 450,
                'max_guests'          => 20,
                'amenities'           => ['braai', 'pool', 'wifi', 'hiking_trails', 'self_catering'],
                'description'         => 'Self-catering guesthouse on a working farm at the edge of the Cederberg. Stunning rock formations, fynbos, and dark skies. Small groups only.',
                'group_notes'         => 'Sole-use rental available for groups up to 20. Bring your own food — nearest shop 25km away.',
            ],

            // ── Waterberg ────────────────────────────────────────────────────
            [
                'name'                => 'Marakele National Park Chalets',
                'type'                => 'chalet',
                'region'              => 'Waterberg',
                'location'            => 'Marakele National Park, Limpopo',
                'avg_cost_per_person' => 490,
                'max_guests'          => 60,
                'amenities'           => ['braai', 'hiking_trails', 'guided_hikes', 'game_viewing'],
                'description'         => 'SANParks chalets inside Marakele. Big 5 game reserve with an outstanding mountain trail (Tlopi Trail). Rare combination of hiking and wildlife.',
                'phone'               => '012 428 9111',
                'group_notes'         => 'Book via SANParks. Group chalets sleep 4–6. Guided day walks and overnight trail available.',
            ],
            [
                'name'                => 'Entabeni Safari Conservancy',
                'type'                => 'lodge',
                'region'              => 'Waterberg',
                'location'            => 'Vaalwater, Limpopo',
                'avg_cost_per_person' => 1800,
                'max_guests'          => 40,
                'amenities'           => ['pool', 'restaurant', 'bar', 'guided_hikes', 'game_viewing', 'spa', 'breakfast_included'],
                'description'         => 'Premium lodge in the Waterberg biosphere. Guided bush walks, rock art sites, and big 5. Best for groups wanting a luxury combine of hiking and safari.',
                'group_notes'         => 'Group packages for 15+ include guided trail + game drive combo. All-inclusive pricing simplifies group billing.',
            ],

            // ── Pilgrim's Rest / Blyde River ────────────────────────────────
            [
                'name'                => 'Aventura Swadini',
                'type'                => 'resort',
                'region'              => 'Blyde River Canyon',
                'location'            => 'Hoedspruit, Limpopo',
                'avg_cost_per_person' => 420,
                'max_guests'          => 120,
                'amenities'           => ['pool', 'restaurant', 'braai', 'chalets', 'hiking_trails'],
                'description'         => 'Resort at the base of the Blyde River Canyon. Close to Bourke\'s Luck Potholes and Pinnacle viewpoints. Good base for Blyde River Canyon rim trails.',
                'group_notes'         => 'Group chalets and dormitory options. Popular for Joburg groups doing the Panorama Route.',
            ],

            // ── Wilderness / Garden Route ────────────────────────────────────
            [
                'name'                => 'Otter Trail Huts (SANParks)',
                'type'                => 'camp',
                'region'              => 'Garden Route',
                'location'            => 'Tsitsikamma National Park, Western Cape',
                'avg_cost_per_person' => 320,
                'max_guests'          => 12,
                'amenities'           => ['hiking_trails', 'coastal_views'],
                'description'         => 'Five overnight huts on the iconic 5-day Otter Trail along the Wild Coast. One of SA\'s most sought-after overnight hiking trails. Bookings open 12 months in advance.',
                'phone'               => '012 428 9111',
                'group_notes'         => 'Max 12 people per group. Permit system via SANParks. Book a full year ahead. No cell signal on trail.',
            ],
            [
                'name'                => 'Garden Route Hiking Lodge',
                'type'                => 'lodge',
                'region'              => 'Garden Route',
                'location'            => 'Wilderness, Western Cape',
                'avg_cost_per_person' => 560,
                'max_guests'          => 30,
                'amenities'           => ['braai', 'pool', 'wifi', 'hiking_trails', 'breakfast_included'],
                'description'         => 'Comfortable lodge near the Wilderness wetlands and Outeniqua trail network. Route planning support, packed lunches, and gear drying room.',
                'group_notes'         => 'Group rate for 15+ includes packed lunches and a route briefing from the owner (avid hiker).',
            ],

            // ── Pilanesberg ────────────────────────────────────────────────
            [
                'name'                => 'Bakubung Bush Lodge',
                'type'                => 'lodge',
                'region'              => 'Pilanesberg',
                'location'            => 'Pilanesberg National Park, North West',
                'avg_cost_per_person' => 1400,
                'max_guests'          => 80,
                'amenities'           => ['pool', 'restaurant', 'bar', 'game_viewing', 'guided_hikes', 'breakfast_included'],
                'description'         => 'Award-winning lodge inside Pilanesberg with Big 5 and prolific birdlife. Guided bush walks and game drives. Close to Sun City — popular with Joburg groups.',
                'group_notes'         => 'Group packages include guided morning trail + evening game drive. 1.5h from Sandton.',
            ],

        ];

        foreach ($spots as $data) {
            Accommodation::updateOrCreate(
                ['name' => $data['name'], 'region' => $data['region']],
                $data
            );
        }

        $this->command->info('✅ Accommodations seeded — ' . count($spots) . ' hiking-friendly spots.');
    }
}
