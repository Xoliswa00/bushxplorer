<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $plan->title }} — BushXplorer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Dancing+Script:wght@500;700&family=Cinzel:wght@400;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:          #0a160d;
            --bg-mid:      #0f2117;
            --bg-card:     #0d1e13;
            --gold:        #c9a84c;
            --gold-light:  #e8d08a;
            --gold-pale:   #f5ebb0;
            --cream:       #f5f0e8;
            --cream-muted: #c8bfa8;
            --leaf-dark:   #0a2e16;
            --leaf-mid:    #134d28;
            --leaf-light:  #1e6e3c;
            --poster-w:    600px;
        }

        body {
            background: #1a1a1a;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 16px 48px;
            font-family: 'Inter', sans-serif;
        }

        /* ── Action bar ───────────────────────────────────────── */
        .action-bar {
            width: var(--poster-w);
            max-width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .action-bar a.back { color: #888; text-decoration: none; font-size: 13px; }
        .action-bar a.back:hover { color: #ccc; }
        .action-bar .btns { display: flex; gap: 8px; }
        .btn-print {
            padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: 1px solid #444; background: #2a2a2a; color: #ccc;
            text-decoration: none; transition: all .15s;
        }
        .btn-print:hover { background: #333; }
        .btn-dl {
            padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
            cursor: pointer; border: none; background: var(--gold); color: #0a0a0a;
            text-decoration: none; transition: all .15s;
        }
        .btn-dl:hover { background: var(--gold-light); }

        /* ── Poster card ─────────────────────────────────────── */
        .poster {
            width: var(--poster-w);
            max-width: 100%;
            background: var(--bg-card);
            position: relative;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,.8);
        }

        /* ── Gold accent topbar ──────────────────────────────── */
        .poster__topbar {
            height: 5px;
            background: linear-gradient(90deg, transparent, var(--gold), var(--gold-light), var(--gold), transparent);
        }

        /* ── Hero ─────────────────────────────────────────────── */
        .poster__hero {
            position: relative;
            background: linear-gradient(175deg, var(--bg) 0%, #0d2819 55%, #081410 100%);
            padding: 36px 48px 0;
            min-height: 340px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Corner leaf clusters */
        .leaf-tl, .leaf-tr {
            position: absolute;
            top: 0;
            width: 180px;
            height: 200px;
            pointer-events: none;
        }
        .leaf-tl { left: 0; }
        .leaf-tr { right: 0; transform: scaleX(-1); }

        /* Brand intro line */
        .brand-line {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
            position: relative;
            z-index: 2;
        }
        .brand-line::before, .brand-line::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold));
        }
        .brand-line::after { background: linear-gradient(90deg, var(--gold), transparent); }
        .brand-line span {
            font-family: 'Cinzel', serif;
            font-size: 9px;
            letter-spacing: 3.5px;
            color: var(--gold);
            font-weight: 600;
            white-space: nowrap;
        }

        /* Event title */
        .event-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(36px, 9vw, 56px);
            font-weight: 700;
            color: var(--cream);
            text-align: center;
            line-height: 1.1;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 20px rgba(0,0,0,.4);
        }

        /* Tagline */
        .tagline {
            font-family: 'Dancing Script', cursive;
            font-size: 24px;
            font-weight: 500;
            color: var(--gold-light);
            text-align: center;
            margin-top: 8px;
            position: relative;
            z-index: 2;
            opacity: .9;
        }

        /* Badges row */
        .badges {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            position: relative;
            z-index: 2;
        }
        .badge {
            font-family: 'Cinzel', serif;
            font-size: 9px;
            letter-spacing: 2px;
            font-weight: 600;
            padding: 5px 14px;
            border: 1px solid;
            border-radius: 999px;
            text-transform: uppercase;
        }
        .badge--difficulty {
            border-color: var(--gold);
            color: var(--gold);
            background: rgba(201,168,76,.08);
        }
        .badge--type {
            border-color: rgba(245,240,232,.3);
            color: var(--cream-muted);
        }

        /* Mountain SVG panorama */
        .mountain-panorama {
            width: 100%;
            margin-top: 18px;
            display: block;
            flex-shrink: 0;
        }

        /* ── Gold divider ─────────────────────────────────────── */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 40px;
            margin: 0;
        }
        .divider__line { flex: 1; height: 1px; background: linear-gradient(90deg, transparent, var(--gold), transparent); }
        .divider__gem {
            width: 8px; height: 8px;
            background: var(--gold);
            transform: rotate(45deg);
            flex-shrink: 0;
        }

        /* ── Body ─────────────────────────────────────────────── */
        .poster__body {
            padding: 28px 44px 24px;
            background: linear-gradient(180deg, #0f2117 0%, #0a160d 100%);
        }

        /* Info rows */
        .info-list { list-style: none; margin-bottom: 24px; }
        .info-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 7px 0;
            border-bottom: 1px solid rgba(201,168,76,.1);
            font-family: 'Inter', sans-serif;
        }
        .info-list li:last-child { border-bottom: none; }
        .info-icon {
            width: 30px; height: 30px; flex-shrink: 0;
            border: 1px solid rgba(201,168,76,.3);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            background: rgba(201,168,76,.05);
        }
        .info-label {
            font-family: 'Cinzel', serif;
            font-size: 8px;
            letter-spacing: 2px;
            color: var(--gold);
            text-transform: uppercase;
            display: block;
            margin-bottom: 1px;
        }
        .info-value {
            font-size: 13px;
            font-weight: 500;
            color: var(--cream);
            line-height: 1.4;
        }
        .info-sub {
            font-size: 11px;
            color: var(--cream-muted);
            margin-top: 1px;
        }

        /* 3-col specs */
        .specs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 22px;
        }
        .spec {
            text-align: center;
            padding: 14px 8px;
            border: 1px solid rgba(201,168,76,.2);
            border-radius: 10px;
            background: rgba(201,168,76,.04);
        }
        .spec--highlight {
            border-color: var(--gold);
            background: rgba(201,168,76,.1);
        }
        .spec__label {
            font-family: 'Cinzel', serif;
            font-size: 8px;
            letter-spacing: 2px;
            color: var(--gold);
            text-transform: uppercase;
            display: block;
            margin-bottom: 5px;
        }
        .spec__value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--cream);
            line-height: 1;
        }
        .spec--highlight .spec__value { color: var(--gold-light); }
        .spec__sub {
            font-size: 10px;
            color: var(--cream-muted);
            margin-top: 2px;
        }

        /* Transport section */
        .transport-box {
            border: 1px solid rgba(201,168,76,.3);
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 22px;
            background: rgba(201,168,76,.04);
        }
        .transport-box__header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }
        .transport-box__title {
            font-family: 'Cinzel', serif;
            font-size: 9px;
            letter-spacing: 2px;
            color: var(--gold);
            text-transform: uppercase;
        }
        .transport-stops { list-style: none; }
        .transport-stops li {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--cream-muted);
            padding: 4px 0;
            border-bottom: 1px solid rgba(255,255,255,.05);
        }
        .transport-stops li:last-child { border: none; }
        .transport-stops .stop-name { color: var(--cream); font-weight: 500; }

        /* ── Footer ───────────────────────────────────────────── */
        .poster__footer {
            padding: 18px 44px;
            background: var(--bg);
            border-top: 1px solid rgba(201,168,76,.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        .footer__logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .footer__logo-icon {
            width: 32px; height: 32px;
            border: 1.5px solid var(--gold);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
        }
        .footer__brand {
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 1px;
        }
        .footer__tagline {
            font-family: 'Dancing Script', cursive;
            font-size: 11px;
            color: var(--cream-muted);
        }
        .footer__url {
            font-size: 10px;
            color: var(--cream-muted);
            font-family: 'Inter', sans-serif;
            text-align: right;
        }
        .footer__url strong { color: var(--gold); display: block; font-size: 11px; margin-bottom: 1px; }

        /* ── Gold bottom bar ─────────────────────────────────── */
        .poster__bottombar {
            height: 5px;
            background: linear-gradient(90deg, transparent, var(--gold), var(--gold-light), var(--gold), transparent);
        }

        /* ── Print styles ────────────────────────────────────── */
        @media print {
            body { background: white; padding: 0; }
            .action-bar { display: none; }
            .poster { box-shadow: none; width: 100%; max-width: none; }
            :root { --poster-w: 100%; }
        }

        /* ── Mobile ──────────────────────────────────────────── */
        @media (max-width: 640px) {
            .action-bar { width: 100%; }
            .poster__body { padding: 22px 24px 18px; }
            .poster__hero { padding: 28px 24px 0; }
        }
    </style>
</head>
<body>

    {{-- Action bar --}}
    <div class="action-bar">
        <a class="back" href="{{ route('planner.index') }}">&#8592; Plans</a>
        <div class="btns">
            <button class="btn-print" onclick="window.print()">🖨 Print</button>
            <a class="btn-dl" href="{{ route('planner.poster.download', $plan->id) }}">&#8595; Download PDF</a>
        </div>
    </div>

    {{-- ════ POSTER CARD ════ --}}
    <div class="poster">

        <div class="poster__topbar"></div>

        {{-- ── HERO ── --}}
        <div class="poster__hero">

            {{-- Top-left leaf cluster --}}
            <svg class="leaf-tl" viewBox="0 0 180 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Main large frond -->
                <path d="M 10,180 C 10,180 -5,120 20,80 C 40,45 90,30 110,10" stroke="#1e6e3c" stroke-width="1.5" fill="none" opacity="0.7"/>
                <path d="M 20,80 C 30,70 55,68 65,55" stroke="#1e6e3c" stroke-width="1" fill="none" opacity="0.5"/>
                <path d="M 50,105 C 60,90 80,88 90,72" stroke="#134d28" stroke-width="1" fill="none" opacity="0.5"/>
                <!-- Leaf 1 -->
                <path d="M 10,160 C 5,140 -10,110 15,95 C 30,85 55,95 45,115 C 38,130 15,140 10,160 Z" fill="#0f3d1e" opacity="0.85"/>
                <!-- Leaf 2 -->
                <path d="M 30,130 C 20,110 5,80 35,65 C 55,55 80,68 65,92 C 55,108 35,115 30,130 Z" fill="#134d28" opacity="0.75"/>
                <!-- Leaf 3 -->
                <path d="M 55,90 C 45,68 40,38 72,28 C 92,22 110,40 90,62 C 76,78 58,78 55,90 Z" fill="#1a5e30" opacity="0.65"/>
                <!-- Small accent leaf -->
                <path d="M 80,55 C 70,38 75,18 100,15 C 115,13 120,28 105,40 C 96,48 83,48 80,55 Z" fill="#1e6e3c" opacity="0.5"/>
                <!-- Vein lines on leaf 1 -->
                <path d="M 22,125 C 28,120 35,118 40,112" stroke="#0a2e16" stroke-width="0.8" fill="none" opacity="0.9"/>
                <path d="M 18,143 C 25,135 33,128 42,120" stroke="#0a2e16" stroke-width="0.8" fill="none" opacity="0.9"/>
                <!-- Gold dust dots -->
                <circle cx="95" cy="25" r="1.5" fill="#c9a84c" opacity="0.4"/>
                <circle cx="112" cy="38" r="1" fill="#c9a84c" opacity="0.3"/>
                <circle cx="70" cy="22" r="1" fill="#c9a84c" opacity="0.25"/>
            </svg>

            {{-- Top-right leaf cluster (mirrored via CSS) --}}
            <svg class="leaf-tr" viewBox="0 0 180 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 10,180 C 10,180 -5,120 20,80 C 40,45 90,30 110,10" stroke="#1e6e3c" stroke-width="1.5" fill="none" opacity="0.7"/>
                <path d="M 20,80 C 30,70 55,68 65,55" stroke="#1e6e3c" stroke-width="1" fill="none" opacity="0.5"/>
                <path d="M 50,105 C 60,90 80,88 90,72" stroke="#134d28" stroke-width="1" fill="none" opacity="0.5"/>
                <path d="M 10,160 C 5,140 -10,110 15,95 C 30,85 55,95 45,115 C 38,130 15,140 10,160 Z" fill="#0f3d1e" opacity="0.85"/>
                <path d="M 30,130 C 20,110 5,80 35,65 C 55,55 80,68 65,92 C 55,108 35,115 30,130 Z" fill="#134d28" opacity="0.75"/>
                <path d="M 55,90 C 45,68 40,38 72,28 C 92,22 110,40 90,62 C 76,78 58,78 55,90 Z" fill="#1a5e30" opacity="0.65"/>
                <path d="M 80,55 C 70,38 75,18 100,15 C 115,13 120,28 105,40 C 96,48 83,48 80,55 Z" fill="#1e6e3c" opacity="0.5"/>
                <path d="M 22,125 C 28,120 35,118 40,112" stroke="#0a2e16" stroke-width="0.8" fill="none" opacity="0.9"/>
                <path d="M 18,143 C 25,135 33,128 42,120" stroke="#0a2e16" stroke-width="0.8" fill="none" opacity="0.9"/>
                <circle cx="95" cy="25" r="1.5" fill="#c9a84c" opacity="0.4"/>
                <circle cx="112" cy="38" r="1" fill="#c9a84c" opacity="0.3"/>
                <circle cx="70" cy="22" r="1" fill="#c9a84c" opacity="0.25"/>
            </svg>

            {{-- "BushXplorer Presents" line --}}
            <div class="brand-line">
                <span>BushXplorer&nbsp;&nbsp;Presents</span>
            </div>

            {{-- Event title --}}
            <h1 class="event-title">{{ $plan->title }}</h1>

            {{-- Tagline --}}
            @if($plan->tagline)
            <p class="tagline">{{ $plan->tagline }}</p>
            @endif

            {{-- Badges --}}
            <div class="badges">
                @if($plan->difficulty)
                <span class="badge badge--difficulty">{{ ucfirst($plan->difficulty) }}</span>
                @endif
                @if($plan->type !== 'hike')
                <span class="badge badge--type">{{ ucfirst(str_replace('_', ' ', $plan->type)) }}</span>
                @endif
            </div>

            {{-- Mountain panorama --}}
            <svg class="mountain-panorama" viewBox="0 0 600 160" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <!-- Sky gradient -->
                <defs>
                    <linearGradient id="skyGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#0d2819" stop-opacity="0"/>
                        <stop offset="100%" stop-color="#0d2819" stop-opacity="0.2"/>
                    </linearGradient>
                    <linearGradient id="mtn1" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#1a4d2e"/>
                        <stop offset="100%" stop-color="#0f3322"/>
                    </linearGradient>
                    <linearGradient id="mtn2" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#0d2818"/>
                        <stop offset="100%" stop-color="#081410"/>
                    </linearGradient>
                    <linearGradient id="mtn3" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#081510"/>
                        <stop offset="100%" stop-color="#050e09"/>
                    </linearGradient>
                </defs>
                <!-- Far range (lightest) -->
                <path d="M 0,100 L 35,62 L 65,78 L 95,50 L 130,70 L 170,35 L 210,60 L 250,28 L 290,55 L 335,22 L 370,52 L 410,30 L 450,58 L 490,38 L 530,55 L 565,40 L 600,52 L 600,160 L 0,160 Z"
                    fill="url(#mtn1)" opacity="0.55"/>
                <!-- Mid range -->
                <path d="M 0,125 L 45,80 L 85,105 L 130,70 L 175,95 L 220,58 L 265,88 L 310,52 L 355,82 L 395,60 L 440,85 L 480,62 L 520,88 L 558,68 L 600,80 L 600,160 L 0,160 Z"
                    fill="url(#mtn2)" opacity="0.75"/>
                <!-- Near range (darkest) -->
                <path d="M 0,155 L 30,118 L 65,140 L 110,105 L 155,130 L 200,100 L 250,125 L 295,98 L 345,122 L 385,103 L 430,128 L 470,108 L 510,130 L 548,112 L 600,125 L 600,160 L 0,160 Z"
                    fill="url(#mtn3)"/>
                <!-- Snow/highlight on tallest peak -->
                <path d="M 250,28 L 260,40 L 240,40 Z" fill="rgba(245,240,232,0.12)"/>
                <path d="M 335,22 L 347,36 L 323,36 Z" fill="rgba(245,240,232,0.15)"/>
                <!-- Gold glowing stars -->
                @php
                    $stars = [[485,18],[520,32],[555,20],[540,45],[500,38],[470,28]];
                @endphp
                @foreach($stars as [$sx,$sy])
                <g transform="translate({{ $sx }},{{ $sy }})">
                    <line x1="-3" y1="0" x2="3" y2="0" stroke="#c9a84c" stroke-width="0.8" opacity="0.5"/>
                    <line x1="0" y1="-3" x2="0" y2="3" stroke="#c9a84c" stroke-width="0.8" opacity="0.5"/>
                    <circle r="1" fill="#e8d08a" opacity="0.6"/>
                </g>
                @endforeach
                <!-- Small moon -->
                <path d="M 52,22 C 55,17 62,16 67,19 C 60,17 54,21 52,27 C 49,24 49,26 52,22 Z" fill="#e8d08a" opacity="0.25"/>
            </svg>

        </div>{{-- /hero --}}

        {{-- ── DIVIDER ── --}}
        <div class="divider">
            <div class="divider__line"></div>
            <div class="divider__gem"></div>
            <div class="divider__line"></div>
        </div>

        {{-- ── BODY ── --}}
        <div class="poster__body">

            {{-- Info list --}}
            <ul class="info-list">
                @if($plan->location)
                <li>
                    <div class="info-icon">📍</div>
                    <div>
                        <span class="info-label">Location</span>
                        <span class="info-value">{{ $plan->location }}</span>
                        @if($plan->trail_name)<div class="info-sub">{{ $plan->trail_name }}</div>@endif
                    </div>
                </li>
                @endif

                @if($plan->departs_at)
                <li>
                    <div class="info-icon">📅</div>
                    <div>
                        <span class="info-label">Date &amp; Time</span>
                        <span class="info-value">{{ $plan->departs_at->format('l, d F Y') }}</span>
                        <div class="info-sub">
                            Departs {{ $plan->departs_at->format('H:i') }}
                            @if($plan->returns_at) &mdash; Returns {{ $plan->returns_at->format('H:i') }} @endif
                        </div>
                    </div>
                </li>
                @endif

                @if($plan->meeting_point)
                <li>
                    <div class="info-icon">🗺</div>
                    <div>
                        <span class="info-label">Meeting Point</span>
                        <span class="info-value">{{ $plan->meeting_point }}</span>
                    </div>
                </li>
                @endif
            </ul>

            {{-- 3-col specs --}}
            <div class="specs">
                <div class="spec">
                    <span class="spec__label">Spots</span>
                    <span class="spec__value">{{ $plan->max_capacity ?? '—' }}</span>
                    @if($plan->min_capacity)<div class="spec__sub">min {{ $plan->min_capacity }}</div>@endif
                </div>
                <div class="spec spec--highlight">
                    <span class="spec__label">Price</span>
                    <span class="spec__value">R{{ number_format($plan->price ?? $plan->suggestedPrice(), 0) }}</span>
                    @if($plan->includes_transport)<div class="spec__sub">+ R{{ number_format($plan->transport_fee,0) }} transport</div>@endif
                </div>
                <div class="spec">
                    <span class="spec__label">Points</span>
                    <span class="spec__value">+{{ $plan->points_awarded }}</span>
                    <div class="spec__sub">earned</div>
                </div>
            </div>

            {{-- Transport --}}
            @if($plan->includes_transport && !empty($plan->transport_pickup_points))
            <div class="transport-box">
                <div class="transport-box__header">
                    <span>🚌</span>
                    <span class="transport-box__title">Transport Available &mdash; R{{ number_format($plan->transport_fee, 0) }} per person</span>
                </div>
                <ul class="transport-stops">
                    @foreach($plan->transport_pickup_points as $pp)
                    <li>
                        <span class="stop-name">📌 {{ $pp['name'] }}</span>
                        <span>{{ $pp['departure_time'] }} &bull; {{ $pp['max_seats'] }} seats</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

        </div>

        {{-- ── DIVIDER ── --}}
        <div class="divider">
            <div class="divider__line"></div>
            <div class="divider__gem"></div>
            <div class="divider__line"></div>
        </div>

        {{-- ── FOOTER ── --}}
        <div class="poster__footer">
            <div class="footer__logo">
                <div class="footer__logo-icon">🌿</div>
                <div>
                    <div class="footer__brand">BushXplorer</div>
                    <div class="footer__tagline">Adventure awaits</div>
                </div>
            </div>
            <div class="footer__url">
                @if($plan->status === 'published' && $plan->publishedHike)
                    <strong>Book your spot</strong>
                    {{ config('app.url') }}/hikes/{{ $plan->publishedHike->slug }}
                @else
                    <strong>Coming Soon</strong>
                    {{ config('app.url') }}
                @endif
            </div>
        </div>

        <div class="poster__bottombar"></div>

    </div>{{-- /poster --}}

    <p style="color:#555; font-size:12px; margin-top:16px; text-align:center;">
        Share this link &bull; Download the PDF &bull; Post to your socials
    </p>

</body>
</html>
