<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\EventPlan;
use App\Models\ExplorerLevel;
use App\Models\Hike;
use App\Models\Member;
use App\Models\MemberNotification;
use App\Models\Payment;
use App\Models\PickupPoint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TwelveFallsCircuit2026Seeder extends Seeder
{
    // ── Entry fees (2025 actuals from research) ──────────────────────────────

    private const PERMIT_COSTS = [
        'Lisbon Falls (94m)'                          => ['pax' => 20, 'rate' => 20],
        'Berlin Falls (80m)'                          => ['pax' => 20, 'rate' => 20],
        'Mac Mac Falls (65m)'                         => ['pax' => 20, 'rate' => 30],
        'Lone Creek Falls (68m)'                      => ['pax' => 20, 'rate' => 46],
        'Bridal Veil Falls (70m)'                     => ['pax' => 20, 'rate' => 20],
        'Horseshoe Falls (68m)'                       => ['pax' => 20, 'rate' => 20],
        'Graskop Gorge Lift — Motitsi Falls (70m)'    => ['pax' => 20, 'rate' => 70],
    ];

    public function run(): void
    {
        $levels  = $this->getExplorerLevels();
        $hike    = $this->seedHike();
        $members = $this->getExistingMembers($levels);
        $this->seedBookings($hike, $members, $levels);
        $this->seedEventPlan($hike);
        $this->seedNotifications($hike, $members);

        $this->command->table(
            ['Key', 'Value'],
            [
                ['Hike',           $hike->title],
                ['Trail',          'Panorama Waterfall Circuit — 12 named waterfalls'],
                ['Date',           $hike->departs_at->format('D d M Y') . ' → ' . $hike->returns_at->format('D d M Y')],
                ['Nights',         '2 nights (overnight trip)'],
                ['Accommodation',  $hike->accommodation_name],
                ['Ticket price',   'R' . number_format($hike->price, 2)],
                ['Accomm add-on',  'R' . number_format($hike->accommodation_cost_per_person, 2) . '/person/night'],
                ['Transport',      'R' . number_format($hike->transport_fee, 2) . '/person (optional, coach from Joburg)'],
                ['Distance',       '~22 km over 2 days'],
                ['Elevation',      '580 m total gain'],
                ['Waterfalls',     '12 named — Lisbon, Berlin, Mac Mac, Lone Creek, Bridal Veil, Horseshoe, Mac Mac Pools, Maria Shires, Forest Falls, Graskop Gorge, Pinnacle Rock, Sabie Falls'],
                ['Members',        count($members) . ' bookings seeded'],
                ['Admin login',    'admin@bushxplorer.co.za / password'],
            ]
        );
    }

    // ── 1. Hike ───────────────────────────────────────────────────────────────

    private function seedHike(): Hike
    {
        $hike = Hike::firstOrCreate(
            ['slug' => 'twelve-falls-circuit-mpumalanga-2026'],
            [
                'title'          => 'Twelve Falls Circuit — Mpumalanga 2026',
                'description'    => 'Chase 12 named waterfalls through the misty highlands of Sabie and Graskop on this landmark BushXplorer weekend. ' .
                    'Day 1 follows the Sabie River valley south through indigenous forest: Mac Mac Falls (65m — twin streams split by gold-rush dynamite), ' .
                    'the swimmable Mac Mac Pools, Lone Creek Falls (68m, powerful single curtain), Horseshoe Falls (68m, horseshoe cascade on the Sabie River), ' .
                    'Bridal Veil Falls (70m, delicate veil through fern-carpeted cliffs), and the quiet Maria Shires Falls (35m, forest-hidden, graves of the Shires family on-site). ' .
                    'Day 2 climbs to the Graskop escarpment: Lisbon Falls (94m — tallest in Mpumalanga, twin streams, absolutely thunderous after rains), ' .
                    'Berlin Falls (80m, drops through a natural rock sluice into a vivid blue pool), the 4.5 km Forest Falls circuit, ' .
                    'the Graskop Gorge glass-lift descent to Motitsi Falls (70m), Pinnacle Rock\'s quartzite tower falls, and Sabie Falls in the town itself. ' .
                    'Combined ~22 km of walking, 580 m total elevation gain across 2 days. Moderate — achievable for any regular hiker. ' .
                    'Two nights at Mogodi Lodge on the escarpment edge above Graskop Gorge.',

                'location'       => 'Sabie & Graskop, Mpumalanga',
                'trail_name'     => 'Panorama Waterfall Circuit',
                'difficulty'     => 'moderate',
                'distance_km'    => 22.0,
                'elevation_gain_m' => 580,

                'departs_at'     => Carbon::parse('2026-11-06 05:00:00'),
                'returns_at'     => Carbon::parse('2026-11-08 19:30:00'),
                'meeting_point'  => 'Mogodi Lodge, Louis Trichardt St, Graskop, 1270',

                'price'          => 1250.00,
                'max_capacity'   => 22,
                'min_capacity'   => 15,
                'is_published'   => true,
                'points_awarded' => 25,

                'includes_transport'            => true,
                'transport_fee'                 => 220.00,

                'nights'                        => 2,
                'accommodation_name'            => 'Mogodi Lodge, Graskop',
                'accommodation_cost_per_person' => 420.00,

                'what_is_included' =>
                    '2 nights accommodation at Mogodi Lodge (escarpment views, pool, restaurant). ' .
                    'Certified trail guide for all waterfall hikes both days. ' .
                    'All entry & conservation fees: Lisbon, Berlin, Mac Mac, Lone Creek, Bridal Veil, Horseshoe, Graskop Gorge Lift, Mac Mac Pools, Forest Falls & Pinnacle Rock. ' .
                    'Day 1 & Day 2 trail lunch packs. ' .
                    'Energy snacks and electrolyte sachets each day. ' .
                    'Coach transport from Joburg (if Full Package). ' .
                    'Route maps, safety briefing and emergency first-aid kit.',

                'what_to_bring'  =>
                    'Waterproof hiking boots (mist and spray are constant). ' .
                    'Rain jacket / windbreaker. ' .
                    'Swimsuit & quick-dry towel (Mac Mac Pools, some falls have swim pools). ' .
                    'Daypack 15–20 L. ' .
                    'Personal water bottle (min 2 L per day). ' .
                    'Sunscreen, hat & insect repellent. ' .
                    'Warm fleece/layer — Graskop nights are cold (12–18 °C in November). ' .
                    'Personal medication & snacks. ' .
                    'Camera or waterproof phone case. ' .
                    'Valid SA ID (SA-citizen entry-fee rates at SAFCOL sites). ' .
                    'Small amount of cash (R100–R200) for optional extras.',
            ]
        );

        // Pickup points
        $stops = [
            ['name' => 'Sandton City', 'address' => 'Nelson Mandela Square, Sandton, Joburg',    'departure_time' => '05:00', 'max_seats' => 22, 'notes' => 'Coach departs sharp at 05:00. Arrive by 04:50.',    'sort_order' => 0],
            ['name' => 'Midrand',      'address' => 'Midrand Gautrain Station, Midrand',          'departure_time' => '05:30', 'max_seats' => 15, 'notes' => 'Second stop. ~30 min after Sandton.',              'sort_order' => 1],
            ['name' => 'Menlyn',       'address' => 'Menlyn Park Shopping Centre, Pretoria East', 'departure_time' => '06:15', 'max_seats' => 10, 'notes' => 'Final Joburg/PTA pickup. ETA Graskop ~10:30.',     'sort_order' => 2],
        ];

        if (! $hike->pickupPoints()->exists()) {
            foreach ($stops as $stop) {
                PickupPoint::create(array_merge($stop, ['hike_id' => $hike->id]));
            }
        }

        return $hike;
    }

    // ── 2. Look up existing members ───────────────────────────────────────────

    private function getExistingMembers(array $levels): array
    {
        $emails = [
            'themba.nkosi@email.co.za',
            'zanele.mokoena@email.co.za',
            'sipho.dlamini@email.co.za',
            'naledi.sithole@email.co.za',
            'kagiso.molefe@email.co.za',
            'bongani.zungu@email.co.za',
            'lerato.phiri@email.co.za',
            'thabo.mahlangu@email.co.za',
        ];

        return Member::whereHas('user', fn ($q) => $q->whereIn('email', $emails))
            ->with(['user', 'explorerLevel'])
            ->get()
            ->keyBy(fn ($m) => $m->user->email)
            ->all();
    }

    // ── 3. Bookings ───────────────────────────────────────────────────────────

    private function seedBookings(Hike $hike, array $members, array $levels): void
    {
        $sandton = $hike->pickupPoints()->where('name', 'Sandton City')->first();
        $midrand  = $hike->pickupPoints()->where('name', 'Midrand')->first();

        /*  [email, package, spots, status, hasPaid]  */
        $configs = [
            ['themba.nkosi@email.co.za',   'full', 1, 'confirmed', true],
            ['zanele.mokoena@email.co.za',  'stay', 1, 'confirmed', true],
            ['sipho.dlamini@email.co.za',   'day',  1, 'pending_payment', false],
            ['naledi.sithole@email.co.za',  'full', 2, 'confirmed', true],
            ['kagiso.molefe@email.co.za',   'full', 1, 'payment_uploaded', false],
            ['bongani.zungu@email.co.za',   'stay', 1, 'payment_uploaded', false],
            ['lerato.phiri@email.co.za',    'full', 1, 'confirmed', true],
            ['thabo.mahlangu@email.co.za',  'day',  1, 'confirmed', true],
        ];

        foreach ($configs as [$email, $package, $spots, $status, $paid]) {
            $member = $members[$email] ?? null;
            if (! $member) continue;

            // Skip if already booked on this hike
            if (Booking::where('member_id', $member->id)->where('hike_id', $hike->id)->exists()) {
                continue;
            }

            $discount    = (float) ($member->explorerLevel?->discount_percentage ?? 0);
            $baseTicket  = $hike->price * $spots;
            $discountAmt = round($baseTicket * $discount / 100, 2);
            $ticketFee   = $baseTicket - $discountAmt;

            $withAccomm    = in_array($package, ['stay', 'full']);
            $withTransport = $package === 'full';

            $accommFee    = $withAccomm    ? round($hike->accommodation_cost_per_person * $hike->nights * $spots, 2) : 0;
            $transportFee = $withTransport ? round($hike->transport_fee * $spots, 2) : 0;
            $amountDue    = $ticketFee + $accommFee + $transportFee;

            $pickup = null;
            if ($withTransport) {
                $pickup = in_array($email, ['naledi.sithole@email.co.za', 'lerato.phiri@email.co.za'])
                    ? $midrand
                    : $sandton;
            }

            $booking = Booking::create([
                'member_id'                  => $member->id,
                'hike_id'                    => $hike->id,
                'pickup_point_id'            => $pickup?->id,
                'status'                     => $status,
                'package'                    => $package,
                'spots'                      => $spots,
                'amount_due'                 => $amountDue,
                'amount_paid'                => $paid ? $amountDue : 0,
                'discount_applied'           => $discountAmt,
                'transport_fee_applied'      => $transportFee,
                'accommodation_fee_applied'  => $accommFee,
                'confirmed_at'               => in_array($status, ['confirmed', 'attended']) ? now()->subDays(rand(1, 14)) : null,
                'notes'                      => "Twelve Falls Circuit 2026 — {$member->first_name} ({$spots} spot" . ($spots > 1 ? 's' : '') . ', ' . strtoupper($package) . ' package)',
            ]);

            if ($paid || $status === 'payment_uploaded') {
                Payment::firstOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'member_id'   => $member->id,
                        'amount'      => $amountDue,
                        'method'      => 'eft',
                        'status'      => $paid ? 'verified' : 'uploaded',
                        'reference'   => $booking->booking_ref,
                        'proof_path'  => 'payments/' . $booking->booking_ref . '/proof.pdf',
                        'verified_by' => $paid ? User::where('email', 'admin@bushxplorer.co.za')->value('id') : null,
                        'verified_at' => $paid ? now()->subDays(rand(1, 10)) : null,
                    ]
                );
            }
        }
    }

    // ── 4. EventPlan ─────────────────────────────────────────────────────────

    private function seedEventPlan(Hike $hike): void
    {
        $adminUser = User::where('email', 'admin@bushxplorer.co.za')->firstOrFail();

        if (EventPlan::where('published_hike_id', $hike->id)->exists()) {
            return;
        }

        // 12 expenses — one for each waterfall (nice narrative touch)
        $expenses = [
            ['description' => 'Coach hire Joburg–Graskop–Joburg (return)',       'category' => 'transport',    'amount' => 9800,  'expense_date' => '2026-11-06'],
            ['description' => 'Lisbon Falls permits (20 pax × R20)',             'category' => 'permits',      'amount' => 400,   'expense_date' => '2026-11-07'],
            ['description' => 'Berlin Falls permits (20 pax × R20)',             'category' => 'permits',      'amount' => 400,   'expense_date' => '2026-11-07'],
            ['description' => 'Mac Mac Falls entry (20 pax × R30)',              'category' => 'permits',      'amount' => 600,   'expense_date' => '2026-11-06'],
            ['description' => 'Lone Creek Falls permits (20 pax × R46)',         'category' => 'permits',      'amount' => 920,   'expense_date' => '2026-11-06'],
            ['description' => 'Bridal Veil & Horseshoe Falls (20 pax × R20)',    'category' => 'permits',      'amount' => 800,   'expense_date' => '2026-11-06'],
            ['description' => 'Graskop Gorge Lift / Motitsi (20 pax × R70)',     'category' => 'permits',      'amount' => 1400,  'expense_date' => '2026-11-07'],
            ['description' => 'Certified trail guide fee (2 days + prep)',        'category' => 'equipment',    'amount' => 3800,  'expense_date' => '2026-11-06'],
            ['description' => 'First aid kit, emergency equipment & radio',       'category' => 'equipment',    'amount' => 1400,  'expense_date' => '2026-10-20'],
            ['description' => 'Day 1 trail lunch packs (20 × R95)',               'category' => 'refreshments', 'amount' => 1900,  'expense_date' => '2026-11-06'],
            ['description' => 'Day 2 trail lunch packs + snacks (20 × R105)',     'category' => 'refreshments', 'amount' => 2100,  'expense_date' => '2026-11-07'],
            ['description' => 'Poster design, social media & WhatsApp marketing', 'category' => 'marketing',    'amount' => 950,   'expense_date' => '2026-10-15'],
        ];

        $pickupPoints = $hike->pickupPoints()->orderBy('sort_order')->get()->map(fn ($p) => [
            'name'           => $p->name,
            'address'        => $p->address,
            'departure_time' => $p->departure_time,
            'max_seats'      => $p->max_seats,
            'notes'          => $p->notes,
        ])->toArray();

        EventPlan::create([
            'created_by'   => $adminUser->id,
            'status'       => 'published',
            'current_step' => 5,
            'published_hike_id' => $hike->id,

            // Step 1 — Concept
            'title'        => $hike->title,
            'type'         => 'hike',
            'tagline'      => 'Chasing 12 cascades through Mpumalanga\'s misty highland gorges',
            'description'  => $hike->description,
            'concept_notes' =>
                "The Twelve Falls Circuit is BushXplorer's most ambitious Mpumalanga weekend yet. We chase 12 named waterfalls across the Sabie highlands and Graskop escarpment over two full hiking days.\n\n" .
                "DAY 1 — Sabie Valley (~11 km, 280 m gain): Mac Mac Falls (65m — originally one fall, split by miners with dynamite in the gold rush), Mac Mac Pools (swim stop!), Horseshoe Falls (68m), Lone Creek Falls (68m, boardwalk through forest), Bridal Veil Falls (70m — delicate veil through fern-carpeted cliffs), Maria Shires Falls (35m — hidden gem, forest-silent).\n\n" .
                "DAY 2 — Graskop Plateau (~11 km, 300 m gain): Lisbon Falls (94m — tallest in Mpumalanga, thunderous after spring rains), Berlin Falls (80m, natural rock sluice into vivid blue pool), Forest Falls circuit (4.5 km, Mac Mac River tributary), Graskop Gorge glass-lift descent to Motitsi Falls (70m), Pinnacle Rock quartzite tower falls (30m), Sabie Falls in the town centre.\n\n" .
                "We stay 2 nights at Mogodi Lodge, perched above the Graskop Gorge with escarpment views, a pool, and restaurant. Braai dinner Friday night.",
            'cover_color'  => '#1e3a5f',

            // Step 2 — Logistics
            'location'     => $hike->location,
            'trail_name'   => $hike->trail_name,
            'difficulty'   => $hike->difficulty,
            'departs_at'   => $hike->departs_at,
            'returns_at'   => $hike->returns_at,
            'meeting_point'=> $hike->meeting_point,

            // Step 3 — Capacity & Transport
            'max_capacity'                  => $hike->max_capacity,
            'min_capacity'                  => $hike->min_capacity,
            'points_awarded'                => $hike->points_awarded,
            'includes_transport'            => true,
            'transport_fee'                 => $hike->transport_fee,
            'transport_pickup_points'       => $pickupPoints,
            'nights'                        => $hike->nights,
            'accommodation_name'            => $hike->accommodation_name,
            'accommodation_cost_per_person' => $hike->accommodation_cost_per_person,
            'what_is_included'              => $hike->what_is_included,
            'what_to_bring'                 => $hike->what_to_bring,

            // Step 4 — Financials
            'expenses'         => $expenses,
            'target_margin_pct'=> 15,
            'price'            => $hike->price,
        ]);
    }

    // ── 5. Notifications ─────────────────────────────────────────────────────

    private function seedNotifications(Hike $hike, array $members): void
    {
        $messages = [
            'themba.nkosi@email.co.za'  => ['title' => '🏔 Twelve Falls Circuit — Confirmed!',
                'body' => 'Your FULL package booking for the Twelve Falls Circuit is confirmed. 12 waterfalls, 2 nights at Mogodi Lodge. See you on 6 Nov! 🌊',
                'type' => 'booking_confirmed'],
            'zanele.mokoena@email.co.za'=> ['title' => 'Twelve Falls — STAY Package Confirmed',
                'body' => 'All set for the Twelve Falls Circuit! Your 2-night stay at Mogodi Lodge is locked in. Lisbon Falls (94m) is going to blow your mind.',
                'type' => 'booking_confirmed'],
            'sipho.dlamini@email.co.za' => ['title' => 'Payment Awaited — Twelve Falls Circuit',
                'body' => "Please complete payment for your DAY package booking ({$hike->departs_at->format('d M Y')}). R1,250 via EFT — use your booking ref as reference.",
                'type' => 'payment_pending'],
            'naledi.sithole@email.co.za'=> ['title' => 'Two Spots Confirmed — Twelve Falls!',
                'body' => 'Your 2-spot FULL package is confirmed. 20% Legendary Trailblazer discount applied. R840 saved. Midrand pickup at 05:30 on 6 Nov.',
                'type' => 'booking_confirmed'],
            'kagiso.molefe@email.co.za' => ['title' => 'Proof of Payment Received',
                'body' => 'We received your EFT proof for the Twelve Falls Circuit. Our admin will verify within 24 hours and confirm your spot.',
                'type' => 'payment_uploaded'],
            'bongani.zungu@email.co.za' => ['title' => 'Payment Under Review',
                'body' => 'Your payment proof for the Twelve Falls Circuit STAY package is being reviewed. Confirmation incoming soon!',
                'type' => 'payment_uploaded'],
            'lerato.phiri@email.co.za'  => ['title' => '🌊 Twelve Falls — See You at Midrand!',
                'body' => 'Your FULL package is confirmed. Midrand Gautrain pickup at 05:30 sharp on 6 November. Don\'t forget your swimsuit for Mac Mac Pools!',
                'type' => 'booking_confirmed'],
            'thabo.mahlangu@email.co.za'=> ['title' => 'Twelve Falls DAY Package — Confirmed',
                'body' => '20% Legendary discount applied — you\'re in for R1,000. Meeting at Mogodi Lodge Friday by 10:30 if driving yourself. Epic weekend ahead.',
                'type' => 'booking_confirmed'],
        ];

        foreach ($messages as $email => $msg) {
            $member = $members[$email] ?? null;
            if (! $member) continue;

            MemberNotification::firstOrCreate(
                ['member_id' => $member->id, 'title' => $msg['title']],
                [
                    'type' => $msg['type'],
                    'body' => $msg['body'],
                    'data' => ['hike_title' => $hike->title, 'hike_slug' => $hike->slug],
                    'is_read' => false,
                ]
            );
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function getExplorerLevels(): array
    {
        return ExplorerLevel::all()->keyBy('name')->all();
    }
}
