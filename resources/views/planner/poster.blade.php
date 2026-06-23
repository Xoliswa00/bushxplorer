<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $plan->title }} — BushXplorer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Dancing+Script:wght@500;700&family=Cinzel:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold:       #c9a84c;
            --gold-light: #e8d08a;
            --cream:      #f5f0e8;
            --cream-dim:  #c8bfa8;
            --bg:         #0a160d;
            --bg-mid:     #0f2117;
            --bg-card:    #0d1e13;
            --leaf-dark:  #0a2e16;
            --leaf-mid:   #134d28;
            --leaf-light: #1e6e3c;
            --poster-w:   620px;
        }

        body {
            background: #111;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 16px 60px;
            font-family: 'Inter', sans-serif;
        }

        /* ── Action bar ─────────────────────────────────── */
        .action-bar {
            width: var(--poster-w);
            max-width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }
        .action-bar a.back { color: #666; text-decoration: none; font-size: 13px; }
        .action-bar a.back:hover { color: #aaa; }
        .action-bar .btns { display: flex; gap: 8px; }
        .btn { padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; transition: all .15s; }
        .btn-ghost { border: 1px solid #333; background: #1e1e1e; color: #aaa; }
        .btn-ghost:hover { background: #2a2a2a; }
        .btn-gold { border: none; background: var(--gold); color: #080808; }
        .btn-gold:hover { background: var(--gold-light); }

        /* ── Poster wrapper ─────────────────────────────── */
        .poster {
            width: var(--poster-w);
            max-width: 100%;
            background: var(--bg-card);
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0,0,0,.9), 0 0 0 1px rgba(201,168,76,.08);
        }

        /* ── Gold topbar ────────────────────────────────── */
        .poster__topbar {
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--gold), var(--gold-light), var(--gold), transparent);
        }

        /* ── HERO ───────────────────────────────────────── */
        .poster__hero {
            position: relative;
            height: 340px;
            overflow: hidden;
        }

        .hero__photo {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transform: scale(1.04);
            transition: transform 8s ease;
        }
        .poster:hover .hero__photo { transform: scale(1); }

        /* Gradient overlay — heavy at top + bottom, light in middle */
        .hero__overlay {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(to bottom,
                    rgba(8,20,10,.82) 0%,
                    rgba(8,20,10,.25) 38%,
                    rgba(8,20,10,.30) 62%,
                    rgba(8,20,10,.92) 100%);
        }

        /* Leaf clusters sit above overlay */
        .leaf-tl, .leaf-tr {
            position: absolute;
            top: 0;
            width: 160px;
            height: 180px;
            pointer-events: none;
            z-index: 2;
        }
        .leaf-tl { left: 0; }
        .leaf-tr { right: 0; transform: scaleX(-1); }

        .hero__content {
            position: relative;
            z-index: 3;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 28px 40px 0;
        }

        /* Brand line */
        .brand-line {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
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

        /* Title + tagline */
        .hero__title-block { text-align: center; }
        .hero__title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(30px, 7.5vw, 48px);
            font-weight: 700;
            color: var(--cream);
            line-height: 1.1;
            text-shadow: 0 2px 20px rgba(0,0,0,.5);
        }
        .hero__tagline {
            font-family: 'Dancing Script', cursive;
            font-size: 20px;
            color: var(--gold-light);
            margin-top: 6px;
            opacity: .9;
            text-shadow: 0 1px 8px rgba(0,0,0,.6);
        }

        /* Badges */
        .hero__badges {
            display: flex;
            gap: 8px;
            margin-top: 14px;
            justify-content: center;
        }
        .badge {
            font-family: 'Cinzel', serif;
            font-size: 8.5px;
            letter-spacing: 2px;
            font-weight: 600;
            padding: 4px 13px;
            border: 1px solid;
            border-radius: 999px;
            text-transform: uppercase;
        }
        .badge--diff { border-color: var(--gold); color: var(--gold); background: rgba(201,168,76,.1); }
        .badge--type { border-color: rgba(245,240,232,.3); color: var(--cream-dim); }

        /* Date strip at hero bottom */
        .hero__date-strip {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            padding: 14px 24px;
            background: rgba(8,20,10,.6);
            backdrop-filter: blur(4px);
            border-top: 1px solid rgba(201,168,76,.2);
        }
        .date-strip__item {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 12px;
            color: var(--cream-dim);
        }
        .date-strip__item span.icon { font-size: 13px; }
        .date-strip__item strong { color: var(--cream); font-weight: 600; }
        .date-strip__sep { width: 1px; height: 18px; background: rgba(201,168,76,.2); }

        /* ── SVG fallback (no photo) ────────────────────── */
        .hero__svg-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(175deg, var(--bg) 0%, #0d2819 55%, #081410 100%);
        }

        /* ── SCENE STRIP ────────────────────────────────── */
        .scene-strip {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            height: 130px;
            overflow: hidden;
        }
        .scene-strip__photo {
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }
        .scene-strip__photo::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(8,20,10,.18);
            transition: background .3s;
        }
        .scene-strip__photo:hover::after { background: rgba(8,20,10,.05); }
        /* thin gold dividers between photos */
        .scene-strip__photo + .scene-strip__photo {
            border-left: 1px solid rgba(201,168,76,.25);
        }

        /* ── DIVIDER ────────────────────────────────────── */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 40px;
        }
        .divider__line { flex: 1; height: 1px; background: linear-gradient(90deg, transparent, var(--gold), transparent); }
        .divider__gem { width: 8px; height: 8px; background: var(--gold); transform: rotate(45deg); flex-shrink: 0; }

        /* ── BODY ───────────────────────────────────────── */
        .poster__body {
            padding: 26px 44px 22px;
            background: linear-gradient(180deg, #0f2117 0%, #0a160d 100%);
        }

        /* Info list */
        .info-list { list-style: none; margin-bottom: 22px; }
        .info-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 7px 0;
            border-bottom: 1px solid rgba(201,168,76,.1);
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
        .info-label { font-family: 'Cinzel', serif; font-size: 8px; letter-spacing: 2px; color: var(--gold); text-transform: uppercase; display: block; margin-bottom: 1px; }
        .info-value { font-size: 13px; font-weight: 500; color: var(--cream); line-height: 1.4; }
        .info-sub { font-size: 11px; color: var(--cream-dim); margin-top: 1px; }

        /* Specs grid */
        .specs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .spec {
            text-align: center;
            padding: 14px 8px;
            border: 1px solid rgba(201,168,76,.2);
            border-radius: 10px;
            background: rgba(201,168,76,.04);
        }
        .spec--highlight { border-color: var(--gold); background: rgba(201,168,76,.1); }
        .spec__label { font-family: 'Cinzel', serif; font-size: 8px; letter-spacing: 2px; color: var(--gold); text-transform: uppercase; display: block; margin-bottom: 5px; }
        .spec__value { font-family: 'Cormorant Garamond', serif; font-size: 26px; font-weight: 700; color: var(--cream); line-height: 1; }
        .spec--highlight .spec__value { color: var(--gold-light); }
        .spec__sub { font-size: 10px; color: var(--cream-dim); margin-top: 3px; }

        /* Transport box */
        .transport-box {
            border: 1px solid rgba(201,168,76,.3);
            border-radius: 10px;
            padding: 13px 16px;
            margin-bottom: 14px;
            background: rgba(201,168,76,.04);
        }
        .transport-box__header { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; }
        .transport-box__title { font-family: 'Cinzel', serif; font-size: 9px; letter-spacing: 2px; color: var(--gold); text-transform: uppercase; }
        .transport-stops { list-style: none; }
        .transport-stops li {
            display: flex; justify-content: space-between;
            font-size: 12px; color: var(--cream-dim);
            padding: 4px 0;
            border-bottom: 1px solid rgba(255,255,255,.05);
        }
        .transport-stops li:last-child { border: none; }
        .stop-name { color: var(--cream); font-weight: 500; }

        /* Accommodation box */
        .accom-box {
            border: 1px solid rgba(120,110,200,.35);
            border-radius: 10px;
            padding: 13px 16px;
            margin-bottom: 14px;
            background: rgba(99,80,180,.05);
        }
        .accom-box__header { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
        .accom-box__title { font-family: 'Cinzel', serif; font-size: 9px; letter-spacing: 2px; color: #9b92e8; text-transform: uppercase; }
        .accom-box__name { font-family: 'Cormorant Garamond', serif; font-size: 17px; font-weight: 600; color: var(--cream); margin-bottom: 6px; }
        .accom-box__label { font-family: 'Cinzel', serif; font-size: 8px; letter-spacing: 1.5px; color: #9b92e8; text-transform: uppercase; margin-bottom: 3px; }
        .accom-box__detail { font-size: 11.5px; color: rgba(245,240,232,.65); line-height: 1.7; }

        /* ── WHO'S JOINING ──────────────────────────────── */
        .joiners {
            border: 1px solid rgba(201,168,76,.15);
            border-radius: 12px;
            padding: 16px 18px;
            background: rgba(255,255,255,.02);
            margin-top: 6px;
        }
        .joiners__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .joiners__title { font-family: 'Cinzel', serif; font-size: 9px; letter-spacing: 2px; color: var(--gold); text-transform: uppercase; }
        .joiners__count { font-size: 11px; color: var(--cream-dim); }
        .joiners__avatars {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        .avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 700;
            color: #0a160d;
            flex-shrink: 0;
            border: 2px solid rgba(201,168,76,.3);
        }
        .avatar--overflow {
            background: rgba(201,168,76,.12);
            border-color: rgba(201,168,76,.25);
            color: var(--gold);
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 600;
        }
        .joiners__cta {
            font-size: 12px;
            color: var(--cream-dim);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .joiners__cta .spots-left {
            background: rgba(201,168,76,.12);
            border: 1px solid rgba(201,168,76,.25);
            color: var(--gold);
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 99px;
            white-space: nowrap;
        }
        .joiners__empty {
            font-size: 12px;
            color: var(--cream-dim);
            font-style: italic;
            text-align: center;
            padding: 6px 0;
        }

        /* ── FOOTER ─────────────────────────────────────── */
        .poster__footer {
            padding: 18px 44px;
            background: var(--bg);
            border-top: 1px solid rgba(201,168,76,.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .footer__logo { display: flex; align-items: center; gap: 10px; }
        .footer__logo-icon { width: 32px; height: 32px; border: 1.5px solid var(--gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; }
        .footer__brand { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700; color: var(--gold); letter-spacing: 1px; }
        .footer__tagline { font-family: 'Dancing Script', cursive; font-size: 11px; color: var(--cream-dim); }
        .footer__url { font-size: 10px; color: var(--cream-dim); text-align: right; }
        .footer__url strong { color: var(--gold); display: block; font-size: 11px; margin-bottom: 2px; }

        /* ── Gold bottombar ─────────────────────────────── */
        .poster__bottombar {
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--gold), var(--gold-light), var(--gold), transparent);
        }

        /* ── Print ──────────────────────────────────────── */
        @media print {
            body { background: white; padding: 0; }
            .action-bar { display: none; }
            .poster { box-shadow: none; width: 100%; max-width: none; }
            :root { --poster-w: 100%; }
        }
        @media (max-width: 660px) {
            .action-bar { width: 100%; }
            .poster__body { padding: 20px 22px; }
            .hero__content { padding: 22px 20px 0; }
            .scene-strip { height: 90px; }
        }
    </style>
</head>
<body>

    {{-- Action bar --}}
    <div class="action-bar">
        <a class="back" href="{{ route('planner.index') }}">&#8592; Plans</a>
        <div class="btns">
            <button class="btn btn-ghost" onclick="window.print()">🖨 Print</button>
            <a class="btn btn-gold" href="{{ route('planner.poster.download', $plan->id) }}">&#8595; Download PDF</a>
        </div>
    </div>

    {{-- ════ POSTER CARD ════ --}}
    <div class="poster">

        <div class="poster__topbar"></div>

        {{-- ── HERO ── --}}
        <div class="poster__hero">

            {{-- Photo OR SVG fallback --}}
            @if($plan->cover_image_url)
            <div class="hero__photo" style="background-image: url('{{ $plan->cover_image_url }}');"></div>
            @else
            <div class="hero__svg-bg"></div>
            @endif

            <div class="hero__overlay"></div>

            {{-- Leaf clusters --}}
            <svg class="leaf-tl" viewBox="0 0 160 180" fill="none">
                <path d="M 8,165 C 8,165 -4,108 18,72 C 36,40 80,26 98,8" stroke="#1e6e3c" stroke-width="1.5" fill="none" opacity="0.6"/>
                <path d="M 8,145 C 3,126 -9,99 13,86 C 27,76 50,86 40,104 C 33,118 12,126 8,145 Z" fill="#0f3d1e" opacity="0.85"/>
                <path d="M 26,117 C 16,98 2,70 30,57 C 49,47 72,60 58,83 C 49,97 28,103 26,117 Z" fill="#134d28" opacity="0.75"/>
                <path d="M 49,80 C 39,60 34,33 64,24 C 82,17 99,34 80,55 C 68,68 50,68 49,80 Z" fill="#1a5e30" opacity="0.65"/>
                <circle cx="84" cy="22" r="1.5" fill="#c9a84c" opacity="0.4"/>
                <circle cx="100" cy="34" r="1" fill="#c9a84c" opacity="0.3"/>
            </svg>
            <svg class="leaf-tr" viewBox="0 0 160 180" fill="none" style="transform:scaleX(-1)">
                <path d="M 8,165 C 8,165 -4,108 18,72 C 36,40 80,26 98,8" stroke="#1e6e3c" stroke-width="1.5" fill="none" opacity="0.6"/>
                <path d="M 8,145 C 3,126 -9,99 13,86 C 27,76 50,86 40,104 C 33,118 12,126 8,145 Z" fill="#0f3d1e" opacity="0.85"/>
                <path d="M 26,117 C 16,98 2,70 30,57 C 49,47 72,60 58,83 C 49,97 28,103 26,117 Z" fill="#134d28" opacity="0.75"/>
                <path d="M 49,80 C 39,60 34,33 64,24 C 82,17 99,34 80,55 C 68,68 50,68 49,80 Z" fill="#1a5e30" opacity="0.65"/>
                <circle cx="84" cy="22" r="1.5" fill="#c9a84c" opacity="0.4"/>
                <circle cx="100" cy="34" r="1" fill="#c9a84c" opacity="0.3"/>
            </svg>

            {{-- Hero content --}}
            <div class="hero__content">

                <div class="brand-line">
                    <span>BushXplorer &nbsp; Presents</span>
                </div>

                <div class="hero__title-block">
                    <h1 class="hero__title">{{ $plan->title }}</h1>
                    @if($plan->tagline)
                    <p class="hero__tagline">{{ $plan->tagline }}</p>
                    @endif
                    <div class="hero__badges">
                        @if($plan->difficulty)
                        <span class="badge badge--diff">{{ ucfirst($plan->difficulty) }}</span>
                        @endif
                        @if($plan->type !== 'hike')
                        <span class="badge badge--type">{{ ucfirst(str_replace('_',' ',$plan->type)) }}</span>
                        @endif
                        @if(($plan->nights ?? 0) > 0)
                        <span class="badge badge--type">{{ $plan->nights }}N Overnight</span>
                        @endif
                    </div>
                </div>

                {{-- Date/Location strip --}}
                <div class="hero__date-strip">
                    @if($plan->departs_at)
                    <div class="date-strip__item">
                        <span class="icon">📅</span>
                        <span><strong>{{ $plan->departs_at->format('d M Y') }}</strong></span>
                    </div>
                    <div class="date-strip__sep"></div>
                    @endif
                    @if($plan->location)
                    <div class="date-strip__item">
                        <span class="icon">📍</span>
                        <span><strong>{{ $plan->location }}</strong></span>
                    </div>
                    @endif
                    @if($plan->departs_at)
                    <div class="date-strip__sep"></div>
                    <div class="date-strip__item">
                        <span class="icon">⏰</span>
                        <span>Departs <strong>{{ $plan->departs_at->format('H:i') }}</strong></span>
                    </div>
                    @endif
                </div>

            </div>
        </div>{{-- /hero --}}

        {{-- ── SCENE PHOTO STRIP ── --}}
        @if(!empty($plan->scene_image_urls) && count($plan->scene_image_urls) > 0)
        <div class="scene-strip">
            @foreach(array_slice($plan->scene_image_urls, 0, 3) as $sceneUrl)
            <div class="scene-strip__photo" style="background-image: url('{{ $sceneUrl }}');"></div>
            @endforeach
        </div>
        @endif

        {{-- ── DIVIDER ── --}}
        <div class="divider" style="padding-top:18px; padding-bottom:4px;">
            <div class="divider__line"></div>
            <div class="divider__gem"></div>
            <div class="divider__line"></div>
        </div>

        {{-- ── BODY ── --}}
        <div class="poster__body">

            {{-- Info list --}}
            <ul class="info-list">
                @if($plan->trail_name)
                <li>
                    <div class="info-icon">🥾</div>
                    <div>
                        <span class="info-label">Trail</span>
                        <span class="info-value">{{ $plan->trail_name }}</span>
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

                @if($plan->returns_at)
                <li>
                    <div class="info-icon">🔁</div>
                    <div>
                        <span class="info-label">Returns</span>
                        <span class="info-value">{{ $plan->returns_at->format('l, d F Y') }}</span>
                        <div class="info-sub">Estimated {{ $plan->returns_at->format('H:i') }}</div>
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
                    <span class="spec__label">From</span>
                    <span class="spec__value">R{{ number_format($plan->price ?? $plan->suggestedPrice(), 0) }}</span>
                    @if($plan->includes_transport)
                    <div class="spec__sub">+R{{ number_format($plan->transport_fee, 0) }} transport</div>
                    @endif
                    @if(($plan->nights ?? 0) > 0)
                    <div class="spec__sub" style="color:#9b92e8;">+R{{ number_format(($plan->accommodation_cost_per_person ?? 0) * $plan->nights, 0) }} accom</div>
                    @endif
                </div>
                <div class="spec">
                    <span class="spec__label">Explorer Pts</span>
                    <span class="spec__value">+{{ $plan->points_awarded }}</span>
                    <div class="spec__sub">per person</div>
                </div>
            </div>

            {{-- Transport --}}
            @if($plan->includes_transport && !empty($plan->transport_pickup_points))
            @php $isPrivateCar = count($plan->transport_pickup_points) === 1; @endphp
            <div class="transport-box">
                <div class="transport-box__header">
                    <span>{{ $isPrivateCar ? '🚗' : '🚌' }}</span>
                    <span class="transport-box__title">
                        {{ $isPrivateCar ? 'Private Car Convoy' : 'Transport Available' }}
                        &mdash; R{{ number_format($plan->transport_fee, 0) }}/person
                    </span>
                </div>
                <ul class="transport-stops">
                    @foreach($plan->transport_pickup_points as $pp)
                    <li>
                        <span class="stop-name">📍 {{ $pp['name'] ?? '—' }}</span>
                        <span>
                            {{ $pp['departure_time'] ?? '' }}
                            @isset($pp['max_seats']) &bull; {{ $pp['max_seats'] }} seats @endisset
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Accommodation --}}
            @if(($plan->nights ?? 0) > 0)
            <div class="accom-box">
                <div class="accom-box__header">
                    <span>🛏</span>
                    <span class="accom-box__title">
                        {{ $plan->nights }} Night{{ $plan->nights > 1 ? 's' : '' }} Overnight
                        &mdash; R{{ number_format(($plan->accommodation_cost_per_person ?? 0) * $plan->nights, 0) }}/person
                    </span>
                </div>
                @if($plan->accommodation_name)
                <div class="accom-box__name">{{ $plan->accommodation_name }}</div>
                @endif
                @if($plan->what_is_included)
                <div class="accom-box__label">What's Included</div>
                <div class="accom-box__detail">{{ $plan->what_is_included }}</div>
                @endif
            </div>
            @endif

            {{-- ── WHO'S JOINING ── --}}
            @php
                $spotsLeft = ($plan->max_capacity ?? 0) - $joiners->count();
                $avatarColors = ['#c9a84c','#4a7c59','#2563eb','#7c3aed','#e07b39','#1d6b4e','#92400e','#0369a1'];
            @endphp
            <div class="joiners">
                <div class="joiners__header">
                    <span class="joiners__title">Who's Joining</span>
                    @if($joiners->isNotEmpty())
                    <span class="joiners__count">{{ $joiners->count() }} explorer{{ $joiners->count() > 1 ? 's' : '' }} registered</span>
                    @endif
                </div>

                @if($joiners->isNotEmpty())
                <div class="joiners__avatars">
                    @foreach($joiners->take(9) as $i => $member)
                    <div class="avatar" style="background: {{ $avatarColors[$i % count($avatarColors)] }};" title="{{ $member->full_name }}">
                        {{ strtoupper(substr($member->first_name, 0, 1)) }}
                    </div>
                    @endforeach
                    @if($joiners->count() > 9)
                    <div class="avatar avatar--overflow">+{{ $joiners->count() - 9 }}</div>
                    @endif
                    <div class="joiners__cta" style="margin-left: 8px;">
                        @if($spotsLeft > 0)
                        <span class="spots-left">{{ $spotsLeft }} spot{{ $spotsLeft > 1 ? 's' : '' }} left</span>
                        @else
                        <span class="spots-left" style="background:rgba(220,38,38,.1);border-color:rgba(220,38,38,.3);color:#f87171;">Full</span>
                        @endif
                        — be part of this adventure
                    </div>
                </div>
                @else
                <div class="joiners__empty">
                    No one has registered yet — be the first explorer on this trip.
                </div>
                @if($plan->max_capacity)
                <div style="margin-top:10px; text-align:center;">
                    <span class="spots-left" style="background:rgba(201,168,76,.1);border:1px solid rgba(201,168,76,.25);color:var(--gold);font-size:11px;font-weight:600;padding:4px 14px;border-radius:99px;">
                        {{ $plan->max_capacity }} spots available
                    </span>
                </div>
                @endif
                @endif
            </div>

        </div>{{-- /body --}}

        {{-- ── DIVIDER ── --}}
        <div class="divider" style="padding-top:4px; padding-bottom:4px;">
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

    <p style="color:#444; font-size:12px; margin-top:18px; text-align:center; letter-spacing:.05em;">
        Share this link &nbsp;&bull;&nbsp; Download the PDF &nbsp;&bull;&nbsp; Post to your socials
    </p>

</body>
</html>
