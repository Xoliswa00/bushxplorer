<?php

namespace Database\Seeders;

use App\Models\EventPlan;
use App\Models\Hike;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KrantzkloofGoodFriday2027Seeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@bushxplorer.co.za')->firstOrFail();

        // ── HIKE (draft — not published yet) ──────────────────────────────────
        $hike = Hike::updateOrCreate(
            ['slug' => 'krantzkloof-good-friday-2027'],
            [
                'title'                         => 'Krantzkloof Gorge — Good Friday 2027',
                'slug'                          => 'krantzkloof-good-friday-2027',
                'location'                      => 'Kloof, KwaZulu-Natal',
                'trail_name'                    => 'Kloof Falls Trail & Gorge Walk',
                'description'                   => "Two days inside one of KwaZulu-Natal's most spectacular gorges — 180 m deep, draped in ancient subtropical forest, and just 25 km from Durban. The Good Friday weekend gives us three days to slow down, walk long, and let the canyon do the talking.\n\nDay 1 (Good Friday): Drive down from Joburg in convoy, check in at the guesthouse in Kloof, then a relaxed afternoon intro walk through the gorge viewpoints. Braai and stargazing that evening.\n\nDay 2 (Easter Saturday): Full Kloof Falls Trail — river crossings, the Molweni waterfall, ancient fig trees, and the kind of quiet that only a forest can offer. 8.5 km, moderate with a few hard sections.\n\nDay 3 (Easter Sunday): Short morning Cascade Trail before packing up and driving home.",
                'difficulty'                    => 'moderate',
                'distance_km'                   => 8.50,
                'elevation_gain_m'              => 320,
                'departs_at'                    => Carbon::parse('2027-03-26 05:00:00'),
                'returns_at'                    => Carbon::parse('2027-03-28 17:00:00'),
                'meeting_point'                 => 'Engen Garage, N1 South near Crown Interchange, Johannesburg — 05:00 departure',
                'price'                         => 680.00,
                'max_capacity'                  => 15,
                'min_capacity'                  => 9,
                'is_published'                  => true,
                'is_cancelled'                  => false,
                'points_awarded'                => 20,
                'nights'                        => 2,
                'accommodation_name'            => 'Kloof Canopy Guesthouse, Hillcrest',
                'accommodation_cost_per_person' => 250.00,
                'includes_transport'            => true,
                'transport_fee'                 => 550.00,
                'what_is_included'              => implode("\n", [
                    '• Krantzkloof Nature Reserve entry (both days)',
                    '• Experienced local trail guide',
                    '• Saturday evening braai provisions',
                    '• Morning coffee, rusks & energy drinks (both mornings)',
                    '• Trail snacks & recovery drinks',
                    '• Group first aid & emergency kit',
                    '• Trip coordination & digital trip pack',
                ]),
                'what_to_bring'                 => implode("\n", [
                    '• Hiking boots with ankle support (river crossings expected)',
                    '• Swimwear and quick-dry towel',
                    '• 2 × 1.5 L water (refillable at streams)',
                    '• Day pack with rain jacket',
                    '• Change of clothes & evening layers',
                    '• Sunblock, insect repellent, personal medication',
                    '• Camera — the gorge light at golden hour is extraordinary',
                    '• Positive energy and an empty itinerary',
                ]),
            ]
        );

        // ── EXPENSES (12 line items) ───────────────────────────────────────────
        $expenses = [
            [
                'description' => 'Krantzkloof Nature Reserve entry × 15 (2 days)',
                'category'    => 'entrance_fees',
                'amount'      => 750.00,   // R50/person × 15
            ],
            [
                'description' => 'Kloof Canopy Guesthouse — 4 rooms × 2 nights',
                'category'    => 'accommodation',
                'amount'      => 7_200.00, // R900/room/night × 4 rooms × 2 nights
            ],
            [
                'description' => 'Car 1 fuel — Joburg to Kloof return (~1 200 km)',
                'category'    => 'transport',
                'amount'      => 2_700.00,
            ],
            [
                'description' => 'Car 2 fuel — Joburg to Kloof return (~1 200 km)',
                'category'    => 'transport',
                'amount'      => 2_700.00,
            ],
            [
                'description' => 'Car 3 fuel — Joburg to Kloof return (~1 200 km)',
                'category'    => 'transport',
                'amount'      => 2_700.00,
            ],
            [
                'description' => 'N3 toll fees — 3 cars return (Joburg–Durban)',
                'category'    => 'transport',
                'amount'      => 780.00,   // ~R130/car each way × 2 × 3
            ],
            [
                'description' => 'Saturday evening braai provisions × 15',
                'category'    => 'catering',
                'amount'      => 2_250.00, // R150/person
            ],
            [
                'description' => 'Trail snacks & energy drinks — Day 1 & 2 × 15',
                'category'    => 'catering',
                'amount'      => 2_250.00, // R75/person/day × 2 days
            ],
            [
                'description' => 'Morning coffee, rusks & fruit — 2 mornings × 15',
                'category'    => 'catering',
                'amount'      => 1_350.00, // R45/person/morning × 2
            ],
            [
                'description' => 'Experienced local KZN trail guide (2 days)',
                'category'    => 'guide_fees',
                'amount'      => 1_800.00,
            ],
            [
                'description' => 'First aid kit, emergency whistle & bivvy bag',
                'category'    => 'safety',
                'amount'      => 800.00,
            ],
            [
                'description' => 'Trip design, digital pack & social marketing',
                'category'    => 'marketing',
                'amount'      => 500.00,
            ],
        ];

        // ── EVENT PLAN (fully drafted, status = review) ────────────────────────
        EventPlan::updateOrCreate(
            ['published_hike_id' => $hike->id],
            [
                'created_by'                    => $admin->id,
                'title'                         => 'Krantzkloof Gorge — Good Friday 2027',
                'type'                          => 'multi_day',
                'tagline'                       => 'Three days in a 180 m gorge. No alarm clocks. No traffic. Just trail.',
                'description'                   => $hike->description,
                'concept_notes'                 => <<<'NOTES'
KRANTZKLOOF — THE GORGE THAT EARNS ITS NAME

"Krantz" is Afrikaans for cliff. "Kloof" means gorge. Together they describe exactly what awaits: a 5 km-long canyon carved 180 m deep by the Molweni and Lovu rivers through ancient lava rock, draped from top to bottom in subtropical coastal scarp forest. It sits 25 km from Durban and might as well be another world.

WHY GOOD FRIDAY WEEKEND

The long weekend is four days (Good Friday + Easter Saturday + Easter Sunday + Easter Monday family day). We use three of them. The timing is intentional — the forest is at its lushest after summer rains, the gorge pools are full, and the river crossings are alive. There is something appropriate about spending a reflective public holiday inside a cathedral of trees.

THE CONVOY

Three private cars from Joburg. Meeting at Engen on the N1 South at 05:00. The drive down the N3 through the Drakensberg escarpment is part of the trip — Van Reenen's Pass, the descent into KZN, the humidity shift as you drop toward the coast. Plan for coffee at Harrismith Wimpy (traditional).

Estimated arrival Kloof: 10:30–11:00.

THE TRAILS

Day 1 — Gorge Viewpoint Walk (easy, 3 km)
Afternoon arrival walk. Takes in the main gorge overlooks and the suspension bridge. Good for legs that have been in a car since before sunrise.

Day 2 — Kloof Falls Trail (8.5 km, moderate with hard sections)
The main event. Descends into the gorge via steep wooden staircases, follows the Molweni River upstream through riverine forest, passes the 30 m Kloof Falls, requires 2–3 river crossings (shoes off, poles helpful), and climbs back out through ancient Natal wild fig canopy. Allow 5–6 hours. Bring swimwear — the pools below the falls are cold and extraordinary.

Day 3 — Cascade Trail (5.5 km, easy)
Short Sunday morning walk. Out-and-back through coastal forest above the gorge. Home by 11:00, back in Joburg before dark.

ACCOMMODATION

Kloof Canopy Guesthouse, Hillcrest — 4 rooms, garden setting, braai area. 8 minutes from the reserve entrance. The kind of place where the birds wake you before the alarm does.

WHAT MAKES THIS TRIP WORK

The format — private cars, small group (max 15), 2 nights in one spot — means no logistics chaos. Nobody has to figure out who's in which bus. People can make their own Good Friday plans, then converge. Cars create flexibility: if someone needs to leave early Sunday, they can. Three cars also means three different music playlists and three different snack philosophies, which adds character.

NUMBERS

15 participants max. 9 minimum to proceed (enough to fill 3 cars and cover costs). Entry fees, guide, Saturday braai, and all trail snacks included in the ticket. Accommodation and fuel pooled as add-ons.
NOTES,
                'cover_color'                   => '#1a3c34',
                'cover_image_url'               => 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=1400&h=600&fit=crop&q=80',
                'scene_image_urls'              => [
                    'https://images.unsplash.com/photo-1433086966358-54859d0ed716?w=600&h=400&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1551632811-561732d1e306?w=600&h=400&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=600&h=400&fit=crop&q=80',
                ],

                // Step 2 — Logistics
                'location'                      => 'Kloof, KwaZulu-Natal',
                'trail_name'                    => 'Kloof Falls Trail & Gorge Walk',
                'difficulty'                    => 'moderate',
                'departs_at'                    => Carbon::parse('2027-03-26 05:00:00'),
                'returns_at'                    => Carbon::parse('2027-03-28 17:00:00'),
                'meeting_point'                 => 'Engen Garage, N1 South near Crown Interchange — 05:00',

                // Step 3 — Capacity & Transport
                'max_capacity'                  => 15,
                'min_capacity'                  => 9,
                'points_awarded'                => 20,
                'includes_transport'            => true,
                'transport_fee'                 => 550.00,
                'transport_pickup_points'       => [
                    [
                        'name'           => 'Engen Garage, N1 South — Crown Interchange, Johannesburg',
                        'departure_time' => '05:00',
                        'max_seats'      => 15,
                    ],
                ],

                // Step 3 accommodation
                'nights'                        => 2,
                'accommodation_name'            => 'Kloof Canopy Guesthouse, Hillcrest',
                'accommodation_cost_per_person' => 250.00,
                'what_is_included'              => $hike->what_is_included,
                'what_to_bring'                 => $hike->what_to_bring,

                // Step 4 — Financials
                'expenses'                      => $expenses,
                'target_margin_pct'             => 5.00,
                'price'                         => 680.00,

                // Meta
                'current_step'                  => 5,
                'status'                        => 'published',
                'published_hike_id'             => $hike->id,
                'notes'                         => 'Draft ready for review. Awaiting accommodation confirmation and final car assignments before publishing.',
            ]
        );

        // ── SUMMARY TABLE ──────────────────────────────────────────────────────
        $totalExpenses = collect($expenses)->sum('amount');
        $revenueFullPkg = (680 + 250 * 2 + 550) * 15;

        $this->command->line('');
        $this->command->table(
            ['Key', 'Value'],
            [
                ['Trip',            'Krantzkloof Gorge — Good Friday 2027'],
                ['Trail',           'Kloof Falls Trail & Gorge Walk'],
                ['Date',            'Fri 26 Mar 2027 → Sun 28 Mar 2027 (Good Friday weekend)'],
                ['Nights',          '2 nights (Kloof Canopy Guesthouse, Hillcrest)'],
                ['Transport',       '3 private cars — convoy from Joburg (N3)'],
                ['Meeting point',   'Engen N1 South, 05:00 departure'],
                ['Ticket (base)',   'R680 (entry, guide, catering, safety)'],
                ['Accomm add-on',   'R250/person/night (R500 for 2 nights)'],
                ['Fuel add-on',     'R550/person (pooled, FULL package)'],
                ['FULL package',    'R1 730/person'],
                ['Group size',      'Max 15 (3 cars × 5) | Min 9 to proceed'],
                ['Points earned',   '20 Explorer Points'],
                ['Total expenses',  'R' . number_format($totalExpenses, 2)],
                ['Revenue (FULL)',  'R' . number_format($revenueFullPkg, 2)],
                ['Margin',          'R' . number_format($revenueFullPkg - $totalExpenses, 2)],
                ['Plan status',     'PUBLISHED — live on the platform'],
            ]
        );
        $this->command->line('');
    }
}
