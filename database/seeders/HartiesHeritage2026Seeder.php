<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\EventPlan;
use App\Models\Expense;
use App\Models\ExplorerLevel;
use App\Models\ExplorerPoint;
use App\Models\Hike;
use App\Models\Member;
use App\Models\MemberNotification;
use App\Models\Payment;
use App\Models\PickupPoint;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * ═══════════════════════════════════════════════════════════════
 *  HARTBEESPOORT HERITAGE WEEKEND 2026
 *  Friday 25 – Sunday 27 September 2026
 *  Magalies Mountain Lodge + Magalies Kloof Trail
 * ═══════════════════════════════════════════════════════════════
 *
 * FINANCIAL BREAKDOWN (35 people at full capacity):
 * ──────────────────────────────────────────────────
 *  Hike & logistics expenses    R 10,945
 *  Accommodation (35 × R450/nt × 1 night in seeder*)
 *    *Accommodation is paid per-person at booking
 *  Transport (optional, 25 opt-in × R180)  R 4,500
 *
 *  Ticket price (excl. accommodation): R 350
 *  Accommodation add-on (1 night):     R 450
 *  Transport add-on:                   R 180
 *
 *  Full package (with transport + accommodation): R 980
 *  Day-only + transport:                          R 530
 *  Day-only, self-drive:                          R 350
 */
class HartiesHeritage2026Seeder extends Seeder
{
    public function run(): void
    {
        $levels  = $this->seedExplorerLevels();
        $admin   = $this->seedAdminUser();
        $members = $this->seedMembers($levels);
        $this->seedPastHikes($admin, $members);
        $hike    = $this->seedHeritageHike($admin);
        $this->seedPickupPoints($hike);
        $this->seedExpenses($hike, $admin);
        $this->seedBookings($hike, $members, $levels);
        $this->seedEventPlan($hike, $admin);
        $this->seedNotifications($hike, $members);

        $this->command->newLine();
        $this->command->info('  ✅  Hartbeespoort Heritage 2026 — fully seeded');
        $this->command->table(
            ['Key', 'Value'],
            [
                ['Hike',         $hike->title],
                ['Date',         $hike->departs_at->format('D d M Y') . ' → ' . $hike->returns_at->format('D d M Y')],
                ['Nights',       $hike->nights . ' night (overnight trip)'],
                ['Accommodation',$hike->accommodation_name],
                ['Ticket price', 'R' . number_format($hike->price, 2)],
                ['Accomm add-on','R' . number_format($hike->accommodation_cost_per_person, 2) . '/person/night'],
                ['Transport',    'R' . number_format($hike->transport_fee, 2) . '/person (optional)'],
                ['Members',      count($members)],
                ['Admin login',  'admin@bushxplorer.co.za / password'],
            ]
        );
        $this->command->newLine();
    }

    // ── 1. Explorer Levels ─────────────────────────────────────────────────

    private function seedExplorerLevels(): array
    {
        $rows = [
            ['Trail Starter',          0,    99,   0.00,  '#CD7F32', 'Welcome gift bag on your first hike. Access to all community events.'],
            ['Mountain Walker',        100,  299,  5.00,  '#9CA3AF', '5% discount on all hike tickets. Featured on the member wall.'],
            ['Peak Seeker',            300,  599,  10.00, '#F59E0B', '10% discount + priority booking window (48 hrs early access).'],
            ['Summit Master',          600,  999,  15.00, '#10B981', '15% discount + exclusive advanced trails + free BushXplorer water bottle.'],
            ['Legendary Trailblazer',  1000, null, 20.00, '#7C3AED', '20% discount + 1 free hike per year + branded BushXplorer backpack + VIP trail previews.'],
        ];

        $levels = [];
        foreach ($rows as [$name, $min, $max, $disc, $color, $perks]) {
            $levels[$name] = ExplorerLevel::firstOrCreate(
                ['name' => $name],
                ['min_points' => $min, 'max_points' => $max, 'discount_percentage' => $disc, 'badge_color' => $color, 'perks' => $perks]
            );
        }
        return $levels;
    }

    // ── 2. Admin User ──────────────────────────────────────────────────────

    private function seedAdminUser(): User
    {
        return User::firstOrCreate(
            ['email' => 'admin@bushxplorer.co.za'],
            ['name' => 'Xoliswa Masuku', 'password' => Hash::make('password')]
        );
    }

    // ── 3. Members ─────────────────────────────────────────────────────────

    private function seedMembers(array $levels): array
    {
        // [first, last, email, phone, dob, points, hikes, level, emergency_name, emergency_phone]
        $rows = [
            ['Themba',  'Nkosi',    'themba.nkosi@email.co.za',    '082 123 4567', '1990-04-12', 320,  4,  'Peak Seeker',           'Nomsa Nkosi',    '083 123 4567'],
            ['Zanele',  'Mokoena',  'zanele.mokoena@email.co.za',  '073 123 4568', '1985-09-03', 145,  2,  'Mountain Walker',       'Sipho Mokoena',  '084 123 4568'],
            ['Sipho',   'Dlamini',  'sipho.dlamini@email.co.za',   '066 123 4569', '1995-02-28', 45,   1,  'Trail Starter',         'Busi Dlamini',   '085 123 4569'],
            ['Naledi',  'Sithole',  'naledi.sithole@email.co.za',  '072 123 4570', '1988-07-15', 680,  8,  'Summit Master',         'Thandeka Sithole','086 123 4570'],
            ['Kagiso',  'Molefe',   'kagiso.molefe@email.co.za',   '081 123 4571', '1993-11-22', 200,  3,  'Mountain Walker',       'Refilwe Molefe', '087 123 4571'],
            ['Bongani', 'Zungu',    'bongani.zungu@email.co.za',   '074 123 4572', '1987-06-08', 420,  5,  'Peak Seeker',           'Zodwa Zungu',    '088 123 4572'],
            ['Lerato',  'Phiri',    'lerato.phiri@email.co.za',    '062 123 4573', '1998-01-30', 20,   0,  'Trail Starter',         'Mpho Phiri',     '089 123 4573'],
            ['Thabo',   'Mahlangu', 'thabo.mahlangu@email.co.za',  '082 123 4574', '1982-03-17', 1150, 12, 'Legendary Trailblazer', 'Grace Mahlangu', '082 123 4574'],
        ];

        $members = [];
        foreach ($rows as [$first, $last, $email, $phone, $dob, $points, $hikes, $level, $ecName, $ecPhone]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => "$first $last", 'password' => Hash::make('password')]
            );
            $members[$first] = Member::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name'              => $first,
                    'last_name'               => $last,
                    'phone'                   => $phone,
                    'date_of_birth'           => $dob,
                    'emergency_contact_name'  => $ecName,
                    'emergency_contact_phone' => $ecPhone,
                    'total_points'            => $points,
                    'hikes_attended'          => $hikes,
                    'is_active'               => true,
                    'explorer_level_id'       => $levels[$level]->id,
                ]
            );
        }
        return $members;
    }

    // ── 4. Past hikes (explain member history & points) ────────────────────

    private function seedPastHikes(User $admin, array $members): void
    {
        $past = [
            [
                'slug'       => 'suikerbosrand-march-2026',
                'title'      => 'Suikerbosrand Nature Reserve',
                'desc'       => 'A spectacular circular trail through the Suikerbosrand hills with abundant wildlife and valley views.',
                'location'   => 'Suikerbosrand, Gauteng',
                'trail'      => 'Rhinoceros Trail',
                'difficulty' => 'easy',
                'distance'   => 9.5,
                'elevation'  => 180,
                'departs'    => '2026-03-14 06:00:00',
                'returns'    => '2026-03-14 14:00:00',
                'meeting'    => 'Suikerbosrand Visitors Centre, Heidelberg Rd',
                'price'      => 200.00,
                'nights'     => 0,
                'points'     => 20,
                'attendees'  => ['Naledi', 'Thabo', 'Kagiso', 'Bongani', 'Themba'],
            ],
            [
                'slug'       => 'hennops-river-trail-june-2026',
                'title'      => 'Hennops River Trail',
                'desc'       => 'Follow the crystal-clear Hennops River through dramatic gorges and ancient rock formations.',
                'location'   => 'Hennops Hiking Trail, Centurion',
                'trail'      => 'Gorge Loop',
                'difficulty' => 'moderate',
                'distance'   => 11.0,
                'elevation'  => 290,
                'departs'    => '2026-06-07 06:30:00',
                'returns'    => '2026-06-07 15:30:00',
                'meeting'    => 'Hennops Hiking Trail entrance, Hennopsrivier',
                'price'      => 280.00,
                'nights'     => 0,
                'points'     => 30,
                'attendees'  => ['Naledi', 'Thabo', 'Kagiso', 'Bongani', 'Themba', 'Zanele'],
            ],
        ];

        foreach ($past as $p) {
            $hike = Hike::firstOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'], 'description' => $p['desc'],
                    'location' => $p['location'], 'trail_name' => $p['trail'],
                    'difficulty' => $p['difficulty'], 'distance_km' => $p['distance'],
                    'elevation_gain_m' => $p['elevation'],
                    'departs_at' => $p['departs'], 'returns_at' => $p['returns'],
                    'meeting_point' => $p['meeting'], 'price' => $p['price'],
                    'max_capacity' => 30, 'min_capacity' => 10,
                    'is_published' => true, 'is_cancelled' => false,
                    'points_awarded' => $p['points'],
                    'nights' => $p['nights'],
                    'includes_transport' => false, 'transport_fee' => 0,
                ]
            );

            $seq = 1;
            foreach ($p['attendees'] as $first) {
                if (! isset($members[$first])) continue;
                $member = $members[$first];

                $booking = Booking::firstOrCreate(
                    ['hike_id' => $hike->id, 'member_id' => $member->id],
                    [
                        'booking_ref'  => 'BXB-PAST-' . str_pad($hike->id * 100 + $seq++, 5, '0', STR_PAD_LEFT),
                        'status'       => 'attended',
                        'spots'        => 1,
                        'amount_due'   => $p['price'],
                        'amount_paid'  => $p['price'],
                        'discount_applied' => 0,
                        'confirmed_at' => \Carbon\Carbon::parse($p['departs'])->subDays(4),
                        'attended_at'  => \Carbon\Carbon::parse($p['departs']),
                    ]
                );

                $alreadyPointed = ExplorerPoint::where('member_id', $member->id)
                    ->where('pointable_id', $hike->id)
                    ->where('pointable_type', Hike::class)->exists();

                if (! $alreadyPointed) {
                    ExplorerPoint::create([
                        'member_id'      => $member->id,
                        'points'         => $p['points'],
                        'type'           => 'credit',
                        'reason'         => "Attended: {$p['title']}",
                        'pointable_id'   => $hike->id,
                        'pointable_type' => Hike::class,
                        'running_balance'=> $member->total_points,
                    ]);
                }
            }
        }
    }

    // ── 5. Heritage Hike (2-night overnight trip) ─────────────────────────

    private function seedHeritageHike(User $admin): Hike
    {
        return Hike::firstOrCreate(
            ['slug' => 'magaliesberg-heritage-weekend-2026'],
            [
                'title'       => 'Magaliesberg Heritage Weekend 2026',
                'description' =>
                    "Celebrate Heritage Weekend the way it was meant to — boots on the oldest mountain range in the world, "
                    . "a tent under the stars, and a proper South African braai with your people.\n\n"
                    . "We depart Johannesburg on Friday afternoon and settle into Magalies Mountain Lodge by sunset. "
                    . "Saturday morning we hit the trail — the Kloof Route takes us through dramatic gorges, "
                    . "across stream crossings, and up to a ridge with views that'll make you proud to be South African. "
                    . "Saturday evening: heritage braai (boerewors, pap, chakalaka, malva pudding). "
                    . "Sunday is a slow morning — optional sunrise walk, lodge breakfast, then back home by 14:00.\n\n"
                    . "This is a 3-in-1 experience: adventure, culture, and community.",

                'location'        => 'Hartbeespoort, North West',
                'trail_name'      => 'Magalies Kloof Route',
                'difficulty'      => 'moderate',
                'distance_km'     => 12.50,
                'elevation_gain_m'=> 520,

                // Depart Friday afternoon, return Sunday midday
                'departs_at'      => '2026-09-25 14:00:00',
                'returns_at'      => '2026-09-27 14:00:00',

                'meeting_point'   => 'Magalies Mountain Lodge, R512, Hartbeespoort (gate opens 16:00 Friday)',

                // Pricing
                'price'                      => 350.00,   // hike ticket (covers logistics excl. accommodation)
                'accommodation_cost_per_person' => 450.00,  // per person per night × 1 night
                'nights'                     => 1,
                'accommodation_name'         => 'Magalies Mountain Lodge, Hartbeespoort',

                'max_capacity'    => 35,
                'min_capacity'    => 20,
                'is_published'    => true,
                'is_cancelled'    => false,
                'points_awarded'  => 50,

                'includes_transport' => true,
                'transport_fee'      => 180.00,

                'what_is_included' =>
                    "✓ Trail permits & guide\n"
                    . "✓ Water at trail checkpoints\n"
                    . "✓ Heritage braai Saturday evening (boerewors, pap, chakalaka, drinks)\n"
                    . "✓ Lodge accommodation Saturday night (twin-share rooms)\n"
                    . "✓ Sunday light breakfast at lodge\n"
                    . "✓ BushXplorer branded buff / bandana\n"
                    . "✓ 50 Explorer Points",

                'what_to_bring'    =>
                    "• Hiking boots (mandatory — no takkies on this trail)\n"
                    . "• 2-litre water bottle (minimum)\n"
                    . "• Light overnight bag (max 15 kg incl. sleeping kit)\n"
                    . "• Sleeping bag & pillow (lodge provides beds)\n"
                    . "• Sunscreen, headlamp, light rain jacket\n"
                    . "• Personal snacks & trail mix\n"
                    . "• Heritage outfit / flag — we celebrate who we are!",
            ]
        );
    }

    // ── 6. Pickup points ───────────────────────────────────────────────────

    private function seedPickupPoints(Hike $hike): void
    {
        $stops = [
            [
                'name'           => 'Sandton City (Liberty Entrance)',
                'address'        => 'Sandton City Mall, 5th Street, Sandhurst, Sandton, 2196',
                'latitude'       => -26.1076,
                'longitude'      => 28.0567,
                'departure_time' => '14:00',
                'max_seats'      => 20,
                'notes'          => 'Friday departure — meet at the Liberty entrance. Bus departs sharp, no late arrivals.',
                'sort_order'     => 1,
            ],
            [
                'name'           => 'Brightwater Commons, Randburg',
                'address'        => 'Brightwater Commons, Republic Rd & Sturdee Ave, Randburg, 2194',
                'latitude'       => -26.0667,
                'longitude'      => 27.9833,
                'departure_time' => '14:20',
                'max_seats'      => 15,
                'notes'          => 'Park in the Checkers open-air lot opposite the main entrance.',
                'sort_order'     => 2,
            ],
            [
                'name'           => 'Centurion Mall (Entrance 4)',
                'address'        => 'Centurion Mall, Rabie St, Centurion, 0157',
                'latitude'       => -25.8561,
                'longitude'      => 28.1881,
                'departure_time' => '14:50',
                'max_seats'      => 10,
                'notes'          => 'Pick-up at Entrance 4 next to the Spur. Only 10 seats from Centurion — book early.',
                'sort_order'     => 3,
            ],
        ];

        foreach ($stops as $stop) {
            PickupPoint::firstOrCreate(
                ['hike_id' => $hike->id, 'name' => $stop['name']],
                $stop + ['hike_id' => $hike->id]
            );
        }
    }

    // ── 7. Expenses (hike logistics only — accommodation handled per-booking) ─

    private function seedExpenses(Hike $hike, User $admin): void
    {
        $rows = [
            // [description, amount, category, date]
            ['47-seater minibus hire (JHB–Harties–JHB, Fri–Sun)',   5_500.00, 'transport',    '2026-09-25'],
            ['Magaliesberg trail permits (35 × R52)',                1_820.00, 'permits',      '2026-09-26'],
            ['First aid kit restock, safety vests & torches',          480.00, 'equipment',    '2026-09-20'],
            ['Water & electrolyte sachets (trail supply)',              525.00, 'refreshments', '2026-09-26'],
            ['Heritage braai pack — boerewors, pap, chakalaka',      1_650.00, 'refreshments', '2026-09-26'],
            ['Sunday light breakfast at lodge (35 × R50)',            1_750.00, 'accommodation','2026-09-27'],
            ['Malva pudding & heritage dessert platter',               380.00, 'refreshments', '2026-09-26'],
            ['Route maps, laminated trail cards & safety brief',        150.00, 'marketing',    '2026-09-24'],
            ['BushXplorer branded buffs (35 × R28)',                    980.00, 'marketing',    '2026-09-20'],
            ['Certified mountain guide (Sat trail, sunrise walk)',      900.00, 'other',        '2026-09-26'],
            ['Lodge facilitation fee & cleaning deposit',               600.00, 'other',        '2026-09-25'],
            ['Trail head parking & emergency contingency',              210.00, 'other',        '2026-09-26'],
        ];

        foreach ($rows as [$desc, $amount, $cat, $date]) {
            Expense::firstOrCreate(
                ['hike_id' => $hike->id, 'description' => $desc],
                [
                    'amount'       => $amount,
                    'category'     => $cat,
                    'expense_date' => $date,
                    'recorded_by'  => $admin->id,
                ]
            );
        }
    }

    // ── 8. Bookings & payments ─────────────────────────────────────────────
    //
    //  Three package tiers:
    //   FULL   = ticket + accommodation (1 night) + transport
    //   STAY   = ticket + accommodation only (self-drive)
    //   DAY    = ticket only, self-drive, no overnight
    //
    //  Discount applied from ExplorerLevel.
    // ──────────────────────────────────────────────────────────────────────

    private function seedBookings(Hike $hike, array $members, array $levels): void
    {
        $ticket     = (float) $hike->price;                          // R350
        $accomm     = (float) $hike->accommodation_cost_per_person;  // R450 × nights
        $nights     = (int)   $hike->nights;                        // 1
        $transport  = (float) $hike->transport_fee;                  // R180
        $accommTotal = $accomm * $nights;                            // R450

        // [first, package, spots, status, fullyPaid]
        // package: 'full' | 'stay' | 'day'
        $configs = [
            ['Thabo',   'full', 1, 'confirmed',       true],  // Legend (20%)  + accomm + transport
            ['Naledi',  'full', 1, 'confirmed',       true],  // Summit (15%)  + accomm + transport
            ['Themba',  'full', 1, 'confirmed',       true],  // Peak   (10%)  + accomm + transport
            ['Bongani', 'full', 1, 'payment_uploaded',false], // Peak   (10%)  + accomm + transport, proof uploaded
            ['Kagiso',  'stay', 2, 'confirmed',       true],  // Walker (5%)   × 2 spots + accomm, self-drive
            ['Zanele',  'stay', 1, 'confirmed',       true],  // Walker (5%)   × 1 spot  + accomm, self-drive
            ['Sipho',   'day',  1, 'pending_payment', false], // Starter (0%)  day only  + transport
            ['Lerato',  'day',  1, 'draft',           false], // Starter (0%)  just browsing
        ];

        $seq = 1;
        foreach ($configs as [$first, $package, $spots, $status, $paid]) {
            if (! isset($members[$first])) continue;
            $member   = $members[$first];
            $discount = $member->explorerLevel
                ? (float) $member->explorerLevel->discount_percentage
                : 0;

            $unitTicket    = round($ticket * (1 - $discount / 100), 2);
            $withAccomm    = in_array($package, ['full', 'stay']);
            $withTransport = in_array($package, ['full', 'day']) && $package !== 'day' || $package === 'full';
            // day + sipho uses transport, day lerato self-drive
            if ($first === 'Sipho') $withTransport = true;
            if ($first === 'Lerato') $withTransport = false;

            $amountDue = round(
                ($unitTicket + ($withAccomm ? $accommTotal : 0) + ($withTransport ? $transport : 0)) * $spots,
                2
            );
            $discountAmt = round(($ticket - $unitTicket) * $spots, 2);

            $notesLines = [];
            $notesLines[] = "Package: " . strtoupper($package);
            if ($withAccomm)    $notesLines[] = "Accommodation: {$hike->accommodation_name} ({$nights} night)";
            if ($withTransport) $notesLines[] = "Transport: bus pickup included (R{$transport}/person)";
            if ($discountAmt > 0) $notesLines[] = "Level discount: " . $discount . "% (R{$discountAmt} saved)";

            $booking = Booking::firstOrCreate(
                ['hike_id' => $hike->id, 'member_id' => $member->id],
                [
                    'booking_ref'      => 'BXB-2026-' . str_pad($seq++, 5, '0', STR_PAD_LEFT),
                    'status'           => $status,
                    'spots'            => $spots,
                    'amount_due'       => $amountDue,
                    'amount_paid'      => $paid ? $amountDue : 0,
                    'discount_applied' => $discountAmt,
                    'notes'            => implode("\n", $notesLines),
                    'confirmed_at'     => in_array($status, ['confirmed', 'attended'])
                        ? now()->subDays(rand(2, 10))
                        : null,
                ]
            );

            // Verified payment for fully paid bookings
            if ($paid) {
                Payment::firstOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'member_id'   => $member->id,
                        'amount'      => $amountDue,
                        'method'      => collect(['eft', 'eft', 'eft', 'card'])->random(),
                        'status'      => 'verified',
                        'reference'   => 'BX' . strtoupper(Str::random(8)),
                        'notes'       => "Heritage Weekend 2026 — {$first} ({$spots} spot(s), " . strtoupper($package) . " package)",
                        'verified_by' => 1,
                        'verified_at' => now()->subDays(rand(1, 8)),
                    ]
                );
            }

            // Uploaded (unverified) payment for Bongani
            if ($status === 'payment_uploaded') {
                Payment::firstOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'member_id'  => $member->id,
                        'amount'     => $amountDue,
                        'method'     => 'eft',
                        'status'     => 'uploaded',
                        'reference'  => 'BX' . strtoupper(Str::random(8)),
                        'notes'      => 'Proof of EFT uploaded via app — awaiting admin verification',
                    ]
                );
            }
        }
    }

    // ── 9. EventPlan ───────────────────────────────────────────────────────

    private function seedEventPlan(Hike $hike, User $admin): void
    {
        EventPlan::firstOrCreate(
            ['published_hike_id' => $hike->id],
            [
                'created_by'    => $admin->id,

                // Step 1 — Concept
                'title'         => $hike->title,
                'type'          => 'hike',
                'tagline'       => 'Boots on. Mountains ahead. Heritage alive.',
                'description'   => $hike->description,
                'concept_notes' =>
                    "Heritage Day 2026 falls on Thursday 24 Sept — a lot of members will be off. "
                    . "Harties is 65km from JHB, 45 min drive. Magaliesberg is the oldest mountain range on Earth (~3.5 bn yrs) "
                    . "— incredibly on-brand for Heritage Day.\n\n"
                    . "Overnight stay at Magalies Mountain Lodge so we can do a proper braai Saturday evening instead of rushing home. "
                    . "The lodge has twin-share rooms, braai area, pool. We can negotiate a group rate.\n\n"
                    . "Budget model: ticket covers logistics, accommodation is a separate add-on so members choose "
                    . "whether to stay over or just come for the day hike.",
                'cover_color'   => '#134e4a',

                // Step 2 — Logistics
                'location'      => $hike->location,
                'trail_name'    => $hike->trail_name,
                'difficulty'    => $hike->difficulty,
                'departs_at'    => $hike->departs_at,
                'returns_at'    => $hike->returns_at,
                'meeting_point' => $hike->meeting_point,

                // Step 3 — Capacity & Transport
                'max_capacity'           => $hike->max_capacity,
                'min_capacity'           => $hike->min_capacity,
                'points_awarded'         => $hike->points_awarded,
                'includes_transport'     => true,
                'transport_fee'          => $hike->transport_fee,
                'transport_pickup_points'=> [
                    ['name' => 'Sandton City (Liberty Entrance)', 'address' => 'Sandton City Mall, 5th Street',       'departure_time' => '14:00', 'max_seats' => 20, 'notes' => 'Sharp departure, Friday afternoon'],
                    ['name' => 'Brightwater Commons, Randburg',   'address' => 'Republic Rd & Sturdee Ave, Randburg', 'departure_time' => '14:20', 'max_seats' => 15, 'notes' => ''],
                    ['name' => 'Centurion Mall (Entrance 4)',      'address' => 'Rabie St, Centurion',                 'departure_time' => '14:50', 'max_seats' => 10, 'notes' => 'Limited seats'],
                ],

                // Accommodation (new fields)
                'nights'                     => $hike->nights,
                'accommodation_name'         => $hike->accommodation_name,
                'accommodation_cost_per_person' => $hike->accommodation_cost_per_person,
                'what_is_included'           => $hike->what_is_included,
                'what_to_bring'              => $hike->what_to_bring,

                // Step 4 — Financials
                'expenses'         => [
                    ['description' => '47-seater minibus hire (Fri–Sun)',   'category' => 'transport',    'amount' => 5500],
                    ['description' => 'Trail permits (35 × R52)',            'category' => 'permits',      'amount' => 1820],
                    ['description' => 'First aid kit, safety vests & torches', 'category' => 'equipment', 'amount' => 480],
                    ['description' => 'Water & electrolyte sachets',         'category' => 'refreshments', 'amount' => 525],
                    ['description' => 'Heritage braai pack',                 'category' => 'refreshments', 'amount' => 1650],
                    ['description' => 'Sunday lodge breakfast (35 × R50)',   'category' => 'accommodation','amount' => 1750],
                    ['description' => 'Malva pudding & dessert platter',     'category' => 'refreshments', 'amount' => 380],
                    ['description' => 'Route maps & laminated trail cards',  'category' => 'marketing',    'amount' => 150],
                    ['description' => 'BushXplorer branded buffs (35 × R28)','category' => 'marketing',   'amount' => 980],
                    ['description' => 'Mountain guide & sunrise walk',       'category' => 'other',        'amount' => 900],
                    ['description' => 'Lodge facilitation fee & deposit',    'category' => 'other',        'amount' => 600],
                    ['description' => 'Parking & emergency contingency',     'category' => 'other',        'amount' => 210],
                ],
                'target_margin_pct' => 20.00,
                'price'             => $hike->price,

                // Meta
                'current_step' => 5,
                'status'       => 'published',
                'notes'        => 'Published and live. 8 bookings as of seed date. Accommodation confirmed at Magalies Mountain Lodge.',
            ]
        );
    }

    // ── 10. Notifications ──────────────────────────────────────────────────

    private function seedNotifications(Hike $hike, array $members): void
    {
        $msgs = [
            'Thabo'   => ['Legendary Trailblazer Perks Applied',         '20% discount + R280 ticket. Full package confirmed (trail + lodge + bus). See you at Magalies Mountain Lodge!',                 'booking_confirmed', true],
            'Naledi'  => ['Heritage Weekend Booking Confirmed',           'Summit Master 15% discount applied. Accommodation at Magalies Mountain Lodge Saturday night — pack your sleeping bag.',          'booking_confirmed', true],
            'Themba'  => ['Heritage Weekend Confirmed',                   'Peak Seeker 10% off applied. Full package: trail + lodge overnight + bus pickup at Sandton City 14:00 on Friday.',              'booking_confirmed', false],
            'Bongani' => ['Payment Proof Received',                       'EFT proof uploaded successfully. Our team will verify within 24 hours. Your Heritage Weekend spot is provisionally held.',       'payment_uploaded',  false],
            'Kagiso'  => ['2 Spots Confirmed for Heritage Weekend',       'Mountain Walker 5% discount applied. Self-drive to Magalies Mountain Lodge — gate opens 16:00 Friday 25 Sept.',                'booking_confirmed', false],
            'Zanele'  => ['Heritage Weekend Overnight Confirmed',         'Mountain Walker 5% discount applied. 1 spot confirmed — overnight package. Packing list has been emailed to you.',              'booking_confirmed', false],
            'Sipho'   => ['Payment Required to Confirm Your Spot',        'Your Heritage Hike application (day-only + transport) is received. Please complete your EFT of R530 before 10 Sept.',           'payment_reminder',  false],
            'Lerato'  => ['Welcome to BushXplorer, Lerato!',              'The Magaliesberg Heritage Weekend is the perfect first adventure. Only a few spots remaining — complete your booking today.',   'general',           false],
        ];

        foreach ($msgs as $first => [$title, $body, $type, $isRead]) {
            if (! isset($members[$first])) continue;
            MemberNotification::firstOrCreate(
                ['member_id' => $members[$first]->id, 'title' => $title],
                [
                    'body'       => $body,
                    'type'       => $type,
                    'is_read'    => $isRead,
                    'created_at' => now()->subHours(rand(1, 72)),
                ]
            );
        }
    }
}
