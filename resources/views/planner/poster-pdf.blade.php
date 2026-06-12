<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; background: #fff; color: #1c1917; }

        .header {
            background-color: {{ $plan->cover_color }};
            color: white;
            padding: 40px 48px 32px;
            position: relative;
        }
        .header .brand {
            font-size: 10px; font-weight: 700; letter-spacing: 3px;
            text-transform: uppercase; opacity: 0.7; margin-bottom: 10px;
        }
        .header h1 { font-size: 36px; font-weight: 900; line-height: 1.15; }
        .header .tagline { font-size: 14px; opacity: 0.8; margin-top: 10px; font-style: italic; }
        .header .pills { margin-top: 14px; }
        .pill {
            display: inline-block; padding: 3px 12px; border-radius: 999px;
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; background: rgba(255,255,255,0.2);
            margin-right: 6px;
        }

        .body { padding: 32px 48px; }

        .row { display: table; width: 100%; margin-bottom: 20px; }
        .col-half { display: table-cell; width: 50%; vertical-align: top; padding-right: 16px; }
        .col-half:last-child { padding-right: 0; padding-left: 16px; }

        .info-block .label {
            font-size: 9px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
            color: #a8a29e; margin-bottom: 4px;
        }
        .info-block .value { font-size: 16px; font-weight: 700; color: #1c1917; }
        .info-block .sub { font-size: 12px; color: #78716c; margin-top: 2px; }

        .description { font-size: 12px; color: #57534e; line-height: 1.6; margin-bottom: 20px; border-top: 1px solid #e7e5e4; padding-top: 16px; }

        .specs-row { display: table; width: 100%; margin-bottom: 20px; }
        .spec { display: table-cell; width: 33.33%; text-align: center; padding: 12px; background: #f5f5f4; border-radius: 12px; }
        .spec:nth-child(2) { background-color: {{ $plan->cover_color }}1a; margin: 0 8px; }
        .spec .spec-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #a8a29e; }
        .spec .spec-value { font-size: 22px; font-weight: 900; color: #1c1917; margin-top: 4px; }
        .spec:nth-child(2) .spec-value { color: {{ $plan->cover_color }}; }

        .transport-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 12px 16px; margin-bottom: 16px; }
        .transport-box .t-label { font-size: 10px; font-weight: 700; color: #b45309; margin-bottom: 6px; }
        .transport-box .t-stop { font-size: 11px; color: #44403c; margin-bottom: 3px; }

        .meeting-box { background: #f5f5f4; border-radius: 10px; padding: 10px 16px; font-size: 12px; color: #57534e; margin-bottom: 16px; }

        .footer {
            background-color: {{ $plan->cover_color }};
            color: white;
            padding: 16px 48px;
            display: table;
            width: 100%;
        }
        .footer .brand-f { font-size: 13px; font-weight: 700; display: table-cell; }
        .footer .url-f { font-size: 10px; opacity: 0.7; display: table-cell; text-align: right; vertical-align: middle; }
    </style>
</head>
<body>

    <div class="header">
        <p class="brand">BushXplorer Presents</p>
        <h1>{{ $plan->title }}</h1>
        @if($plan->tagline)
        <p class="tagline">{{ $plan->tagline }}</p>
        @endif
        <div class="pills">
            @if($plan->difficulty)<span class="pill">{{ $plan->difficulty }}</span>@endif
            @if($plan->type !== 'hike')<span class="pill">{{ str_replace('_',' ',$plan->type) }}</span>@endif
        </div>
    </div>

    <div class="body">

        <div class="row">
            <div class="col-half">
                <div class="info-block">
                    <p class="label">Date</p>
                    <p class="value">{{ $plan->departs_at?->format('D, d M Y') ?? 'TBC' }}</p>
                    @if($plan->departs_at)
                    <p class="sub">{{ $plan->departs_at->format('H:i') }}{{ $plan->returns_at ? ' – ' . $plan->returns_at->format('H:i') : '' }}</p>
                    @endif
                </div>
            </div>
            <div class="col-half">
                <div class="info-block">
                    <p class="label">Location</p>
                    <p class="value">{{ $plan->location ?? 'TBC' }}</p>
                    @if($plan->trail_name)<p class="sub">{{ $plan->trail_name }}</p>@endif
                </div>
            </div>
        </div>

        @if($plan->description)
        <p class="description">{{ $plan->description }}</p>
        @endif

        <div class="specs-row">
            <div class="spec">
                <p class="spec-label">Spots</p>
                <p class="spec-value">{{ $plan->max_capacity ?? '—' }}</p>
            </div>
            <div class="spec">
                <p class="spec-label">Price</p>
                <p class="spec-value">R{{ number_format($plan->price ?? $plan->suggestedPrice(), 0) }}</p>
            </div>
            <div class="spec">
                <p class="spec-label">Points</p>
                <p class="spec-value">+{{ $plan->points_awarded }}</p>
            </div>
        </div>

        @if($plan->includes_transport && !empty($plan->transport_pickup_points))
        <div class="transport-box">
            <p class="t-label">Transport Available — R{{ number_format($plan->transport_fee, 0) }}/person</p>
            @foreach($plan->transport_pickup_points as $pp)
            <p class="t-stop">• {{ $pp['name'] }} — {{ $pp['departure_time'] }}</p>
            @endforeach
        </div>
        @endif

        @if($plan->meeting_point)
        <div class="meeting-box"><strong>Meeting point:</strong> {{ $plan->meeting_point }}</div>
        @endif

    </div>

    <div class="footer">
        <span class="brand-f">BushXplorer</span>
        <span class="url-f">
            @if($plan->publishedHike)
                Book: {{ config('app.url') }}/hikes/{{ $plan->publishedHike->slug }}
            @else
                {{ config('app.url') }}
            @endif
        </span>
    </div>

</body>
</html>
