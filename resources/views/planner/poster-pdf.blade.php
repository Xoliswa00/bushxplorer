<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            width: 595px;
            background: #0a160d;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #f5f0e8;
        }

        /* ── Gold topbar ─────────────────────────────────────────── */
        .topbar {
            height: 6px;
            background: #c9a84c;
        }

        /* ── HERO ────────────────────────────────────────────────── */
        .hero {
            background-color: #0d2117;
            padding: 32px 44px 0;
            position: relative;
            min-height: 280px;
        }

        /* Brand line */
        .brand-line {
            text-align: center;
            margin-bottom: 20px;
        }
        .brand-line-inner {
            display: inline-block;
            border-top: 1px solid #c9a84c;
            border-bottom: 1px solid #c9a84c;
            padding: 4px 16px;
        }
        .brand-text {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 8px;
            letter-spacing: 4px;
            color: #c9a84c;
            text-transform: uppercase;
            font-weight: bold;
        }

        /* Title */
        .event-title {
            font-family: DejaVu Serif, Georgia, serif;
            font-size: 46px;
            font-weight: bold;
            color: #f5f0e8;
            text-align: center;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        /* Tagline */
        .tagline {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 16px;
            font-style: italic;
            color: #e8d08a;
            text-align: center;
            margin-bottom: 16px;
            opacity: 0.9;
        }

        /* Badges */
        .badges {
            text-align: center;
            margin-bottom: 0;
        }
        .badge {
            display: inline-block;
            border: 1px solid #c9a84c;
            color: #c9a84c;
            font-size: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: bold;
            padding: 4px 12px;
            margin-right: 6px;
            border-radius: 20px;
        }
        .badge-dim {
            border-color: rgba(245,240,232,0.35);
            color: #c8bfa8;
        }

        /* Mountain SVG band — inline table trick for DomPDF */
        .mountain-band {
            width: 100%;
            margin-top: 18px;
            font-size: 0;
            line-height: 0;
        }
        .mountain-band svg { display: block; width: 100%; }

        /* ── DIVIDER ─────────────────────────────────────────────── */
        .divider {
            text-align: center;
            padding: 2px 44px;
            background: #0a160d;
        }
        .divider-line {
            border-top: 1px solid;
            border-image: linear-gradient(to right, transparent, #c9a84c, transparent) 1;
        }
        .divider-gem {
            display: inline-block;
            width: 8px; height: 8px;
            background: #c9a84c;
            transform: rotate(45deg);
            margin: -4px auto 0;
            position: relative;
        }

        /* ── BODY ────────────────────────────────────────────────── */
        .body {
            background: #0f2117;
            padding: 24px 44px 20px;
        }

        /* Info rows */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-row td { padding: 7px 0; border-bottom: 1px solid rgba(201,168,76,0.12); vertical-align: top; }
        .info-row:last-child td { border-bottom: none; }
        .info-icon-cell { width: 32px; }
        .info-icon-box {
            width: 28px; height: 28px;
            border: 1px solid rgba(201,168,76,0.35);
            border-radius: 6px;
            text-align: center;
            font-size: 13px;
            line-height: 28px;
            background: rgba(201,168,76,0.05);
        }
        .info-label {
            font-size: 7.5px;
            letter-spacing: 2px;
            color: #c9a84c;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
        }
        .info-value { font-size: 13px; font-weight: bold; color: #f5f0e8; }
        .info-sub { font-size: 10.5px; color: #c8bfa8; margin-top: 1px; }

        /* Specs row */
        .specs-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-bottom: 20px; }
        .spec-cell {
            width: 33.33%;
            text-align: center;
            padding: 13px 8px;
            border: 1px solid rgba(201,168,76,0.2);
            border-radius: 8px;
            background: rgba(201,168,76,0.04);
        }
        .spec-cell-hl {
            border-color: #c9a84c;
            background: rgba(201,168,76,0.1);
        }
        .spec-label {
            font-size: 7.5px;
            letter-spacing: 2px;
            color: #c9a84c;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .spec-value {
            font-family: DejaVu Serif, Georgia, serif;
            font-size: 26px;
            font-weight: bold;
            color: #f5f0e8;
            display: block;
        }
        .spec-cell-hl .spec-value { color: #e8d08a; }
        .spec-sub { font-size: 9px; color: #c8bfa8; margin-top: 2px; }

        /* Transport */
        .transport {
            border: 1px solid rgba(201,168,76,0.3);
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 18px;
            background: rgba(201,168,76,0.04);
        }
        .transport-header {
            font-size: 8px;
            letter-spacing: 2px;
            color: #c9a84c;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .transport-table { width: 100%; border-collapse: collapse; }
        .transport-table td { font-size: 11.5px; padding: 4px 0; border-bottom: 1px solid rgba(255,255,255,0.05); color: #c8bfa8; }
        .transport-table td:first-child { color: #f5f0e8; font-weight: bold; }
        .transport-table tr:last-child td { border: none; }

        /* ── FOOTER ─────────────────────────────────────────────── */
        .footer-divider {
            border-top: 1px solid rgba(201,168,76,0.2);
            background: #0a160d;
        }
        .footer {
            background: #0a160d;
            padding: 16px 44px;
        }
        .footer-table { width: 100%; border-collapse: collapse; }
        .footer-brand { font-size: 14px; font-weight: bold; color: #c9a84c; letter-spacing: 1px; }
        .footer-sub { font-size: 10px; color: #c8bfa8; font-style: italic; }
        .footer-url { text-align: right; font-size: 9px; color: #c8bfa8; }
        .footer-url strong { font-size: 11px; color: #c9a84c; display: block; margin-bottom: 2px; }

        /* ── Gold bottombar ──────────────────────────────────────── */
        .bottombar { height: 5px; background: #c9a84c; }
    </style>
</head>
<body>

    <div class="topbar"></div>

    {{-- ── HERO ── --}}
    <div class="hero">
        <div class="brand-line">
            <div class="brand-line-inner">
                <span class="brand-text">BushXplorer &nbsp;&bull;&nbsp; Presents</span>
            </div>
        </div>

        <div class="event-title">{{ $plan->title }}</div>

        @if($plan->tagline)
        <div class="tagline">{{ $plan->tagline }}</div>
        @endif

        <div class="badges">
            @if($plan->difficulty)<span class="badge">{{ ucfirst($plan->difficulty) }}</span>@endif
            @if($plan->type !== 'hike')<span class="badge badge-dim">{{ ucfirst(str_replace('_',' ',$plan->type)) }}</span>@endif
        </div>

        {{-- Mountain panorama --}}
        <div class="mountain-band">
            <svg viewBox="0 0 595 150" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" width="595" height="150">
                <defs>
                    <linearGradient id="m1" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#1a4d2e"/>
                        <stop offset="100%" stop-color="#0f3322"/>
                    </linearGradient>
                    <linearGradient id="m2" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#0d2818"/>
                        <stop offset="100%" stop-color="#081410"/>
                    </linearGradient>
                    <linearGradient id="m3" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#081510"/>
                        <stop offset="100%" stop-color="#050e09"/>
                    </linearGradient>
                </defs>
                <path d="M 0,95 L 32,58 L 60,74 L 90,46 L 125,66 L 162,32 L 200,57 L 240,24 L 278,52 L 320,18 L 360,49 L 398,27 L 438,55 L 475,35 L 516,52 L 555,36 L 595,50 L 595,150 L 0,150 Z" fill="url(#m1)" opacity="0.55"/>
                <path d="M 0,120 L 42,76 L 80,100 L 124,65 L 166,90 L 210,55 L 252,82 L 297,48 L 342,76 L 380,56 L 422,80 L 462,58 L 503,82 L 542,64 L 595,76 L 595,150 L 0,150 Z" fill="url(#m2)" opacity="0.75"/>
                <path d="M 0,148 L 28,112 L 62,134 L 105,100 L 148,124 L 193,96 L 238,118 L 282,92 L 330,116 L 372,98 L 414,122 L 455,104 L 496,124 L 536,108 L 595,120 L 595,150 L 0,150 Z" fill="url(#m3)"/>
                <!-- Snow highlights -->
                <path d="M 240,24 L 252,36 L 228,36 Z" fill="rgba(245,240,232,0.13)"/>
                <path d="M 320,18 L 333,32 L 307,32 Z" fill="rgba(245,240,232,0.16)"/>
                <!-- Stars -->
                <line x1="478" y1="18" x2="484" y2="18" stroke="#c9a84c" stroke-width="0.8" opacity="0.5"/>
                <line x1="481" y1="15" x2="481" y2="21" stroke="#c9a84c" stroke-width="0.8" opacity="0.5"/>
                <circle cx="481" cy="18" r="1.5" fill="#e8d08a" opacity="0.5"/>
                <line x1="515" y1="28" x2="521" y2="28" stroke="#c9a84c" stroke-width="0.8" opacity="0.4"/>
                <line x1="518" y1="25" x2="518" y2="31" stroke="#c9a84c" stroke-width="0.8" opacity="0.4"/>
                <line x1="545" y1="16" x2="549" y2="16" stroke="#c9a84c" stroke-width="0.7" opacity="0.35"/>
                <line x1="547" y1="14" x2="547" y2="18" stroke="#c9a84c" stroke-width="0.7" opacity="0.35"/>
                <!-- Moon -->
                <path d="M 52,20 C 55,15 63,14 68,17 C 61,15 54,19 52,25 C 48,23 49,24 52,20 Z" fill="#e8d08a" opacity="0.22"/>
            </svg>
        </div>
    </div>

    {{-- Divider --}}
    <div class="divider">
        <div class="divider-line"></div>
    </div>

    {{-- ── BODY ── --}}
    <div class="body">

        <table class="info-table">
            @if($plan->location)
            <tr class="info-row">
                <td class="info-icon-cell"><div class="info-icon-box">📍</div></td>
                <td style="padding-left:10px;">
                    <span class="info-label">Location</span>
                    <span class="info-value">{{ $plan->location }}</span>
                    @if($plan->trail_name)<div class="info-sub">{{ $plan->trail_name }}</div>@endif
                </td>
            </tr>
            @endif
            @if($plan->departs_at)
            <tr class="info-row">
                <td class="info-icon-cell"><div class="info-icon-box">📅</div></td>
                <td style="padding-left:10px;">
                    <span class="info-label">Date &amp; Time</span>
                    <span class="info-value">{{ $plan->departs_at->format('l, d F Y') }}</span>
                    <div class="info-sub">Departs {{ $plan->departs_at->format('H:i') }}{{ $plan->returns_at ? ' — Returns '.$plan->returns_at->format('H:i') : '' }}</div>
                </td>
            </tr>
            @endif
            @if($plan->meeting_point)
            <tr class="info-row">
                <td class="info-icon-cell"><div class="info-icon-box">🗺</div></td>
                <td style="padding-left:10px;">
                    <span class="info-label">Meeting Point</span>
                    <span class="info-value">{{ $plan->meeting_point }}</span>
                </td>
            </tr>
            @endif
        </table>

        {{-- Specs --}}
        <table class="specs-table">
            <tr>
                <td class="spec-cell">
                    <span class="spec-label">Spots</span>
                    <span class="spec-value">{{ $plan->max_capacity ?? '—' }}</span>
                    @if($plan->min_capacity)<div class="spec-sub">min {{ $plan->min_capacity }}</div>@endif
                </td>
                <td class="spec-cell spec-cell-hl">
                    <span class="spec-label">Price</span>
                    <span class="spec-value">R{{ number_format($plan->price ?? $plan->suggestedPrice(), 0) }}</span>
                    @if($plan->includes_transport)<div class="spec-sub">+R{{ number_format($plan->transport_fee,0) }} transport</div>@endif
                </td>
                <td class="spec-cell">
                    <span class="spec-label">Points</span>
                    <span class="spec-value">+{{ $plan->points_awarded }}</span>
                    <div class="spec-sub">earned</div>
                </td>
            </tr>
        </table>

        @if($plan->includes_transport && !empty($plan->transport_pickup_points))
        <div class="transport">
            <div class="transport-header">🚌 &nbsp; Transport Available — R{{ number_format($plan->transport_fee, 0) }} per person</div>
            <table class="transport-table">
                @foreach($plan->transport_pickup_points as $pp)
                <tr>
                    <td>📌 {{ $pp['name'] }}</td>
                    <td style="text-align:right;">{{ $pp['departure_time'] }} &bull; {{ $pp['max_seats'] }} seats</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

    </div>

    {{-- ── DIVIDER ── --}}
    <div class="divider">
        <div class="divider-line"></div>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>
                    <div class="footer-brand">🌿 &nbsp; BushXplorer</div>
                    <div class="footer-sub">Adventure awaits</div>
                </td>
                <td class="footer-url">
                    @if($plan->status === 'published' && $plan->publishedHike)
                        <strong>Book your spot</strong>
                        {{ config('app.url') }}/hikes/{{ $plan->publishedHike->slug }}
                    @else
                        <strong>Coming Soon</strong>
                        {{ config('app.url') }}
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="bottombar"></div>

</body>
</html>
