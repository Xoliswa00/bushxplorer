<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BushXplorer — Adventures</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body { font-family:'Inter',sans-serif; background:#f2ece2; margin:0; }
        .serif { font-family:'Cormorant Garamond',serif; }
        * { box-sizing:border-box; }
    </style>
</head>
<body>

{{-- ── NAV ── --}}
<nav style="background:#0b1c0f; position:sticky; top:0; z-index:40;">
    <div style="height:3px; background:linear-gradient(90deg,transparent,#c9a84c,#e8d08a,#c9a84c,transparent);"></div>
    <div style="max-width:1280px; margin:0 auto; padding:14px 24px; display:flex; align-items:center; justify-content:space-between;">
        <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <div style="width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;border:1px solid rgba(201,168,76,0.4);background:rgba(201,168,76,0.1);">🌿</div>
            <div>
                <span class="serif" style="font-size:22px;font-weight:700;color:#c9a84c;letter-spacing:0.02em;">BushXplorer</span>
                <span style="display:block;font-size:9px;text-transform:uppercase;letter-spacing:0.25em;font-weight:500;color:#3d5c43;margin-top:-2px;">Adventures</span>
            </div>
        </a>
        <div style="display:flex;align-items:center;gap:12px;">
            <a href="{{ route('hikes.index') }}" style="font-size:13px;font-weight:500;color:#8aad90;text-decoration:none;">Upcoming Hikes</a>
            @auth
            <a href="{{ route('member.dashboard') }}" style="font-size:13px;font-weight:600;padding:7px 18px;border-radius:10px;background:rgba(201,168,76,0.12);border:1px solid rgba(201,168,76,0.3);color:#c9a84c;text-decoration:none;">My Dashboard</a>
            @else
            <a href="{{ route('login') }}" style="font-size:13px;font-weight:500;color:#8aad90;text-decoration:none;">Sign in</a>
            <a href="{{ route('register') }}" style="font-size:13px;font-weight:600;padding:7px 18px;border-radius:10px;background:#166534;color:#fff;text-decoration:none;">Join Now</a>
            @endauth
        </div>
    </div>
</nav>

{{-- ── HERO ── --}}
<section style="background:#0d1e13; padding:80px 24px; text-align:center; overflow:hidden;">
    <div style="height:1px; background:linear-gradient(90deg,transparent,rgba(201,168,76,0.4),transparent); margin-bottom:48px;"></div>
    <p style="font-size:11px;text-transform:uppercase;letter-spacing:0.3em;color:#4a6a52;font-weight:600;margin-bottom:16px;">South Africa's Premier Hiking Community</p>
    <h1 class="serif" style="font-size:clamp(44px,7vw,84px);font-weight:700;color:#f5f0e8;line-height:1.05;margin:0 0 20px;">
        The Trails Await.<br><em style="color:#c9a84c;">Are You Ready?</em>
    </h1>
    <p style="font-size:16px;color:#6a9070;max-width:560px;margin:0 auto 40px;line-height:1.7;">
        Expert-led hiking adventures across South Africa's most breathtaking landscapes. Book a trail, earn explorer points, and unlock exclusive member discounts.
    </p>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        <a href="{{ route('hikes.index') }}" style="padding:14px 32px;border-radius:12px;font-weight:700;font-size:15px;background:#166534;color:#fff;text-decoration:none;">Browse Hikes →</a>
        @guest
        <a href="{{ route('register') }}" style="padding:14px 32px;border-radius:12px;font-weight:700;font-size:15px;background:transparent;color:#c9a84c;border:1px solid rgba(201,168,76,0.4);text-decoration:none;">Join BushXplorer</a>
        @endguest
    </div>
    <div style="height:1px; background:linear-gradient(90deg,transparent,rgba(201,168,76,0.3),transparent); margin-top:60px;"></div>
</section>

{{-- ── UPCOMING HIKES ── --}}
<section style="max-width:1200px;margin:0 auto;padding:60px 24px;">
    <div style="display:flex;align-items:baseline;justify-content:space-between;margin-bottom:32px;flex-wrap:wrap;gap:12px;">
        <div>
            <p style="font-size:10px;text-transform:uppercase;letter-spacing:0.2em;color:#c9a84c;font-weight:700;margin-bottom:6px;">What's coming up</p>
            <h2 class="serif" style="font-size:36px;font-weight:700;color:#1a1a1a;margin:0;">Upcoming Adventures</h2>
        </div>
        <a href="{{ route('hikes.index') }}" style="font-size:13px;font-weight:600;color:#166534;text-decoration:none;">View all →</a>
    </div>

    @php
        $upcomingHikes = \App\Models\Hike::where('is_published', true)
            ->where('departs_at', '>', now())
            ->orderBy('departs_at')
            ->limit(6)
            ->get();
    @endphp

    @if($upcomingHikes->isEmpty())
    <div style="text-align:center;padding:60px;background:#fff;border-radius:20px;border:2px dashed #e2dbd0;">
        <p style="font-size:32px;margin-bottom:12px;">🥾</p>
        <p style="font-weight:600;color:#374151;">No upcoming hikes yet</p>
        <p style="font-size:14px;color:#9ca3af;margin-top:6px;">Check back soon — new adventures are always in the works.</p>
    </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;">
        @foreach($upcomingHikes as $hike)
        @php
            $diffColor = match($hike->difficulty) {
                'easy'     => ['bg'=>'#dcfce7','text'=>'#166534'],
                'moderate' => ['bg'=>'#fef9c3','text'=>'#854d0e'],
                'hard'     => ['bg'=>'#ffedd5','text'=>'#9a3412'],
                default    => ['bg'=>'#fee2e2','text'=>'#991b1b'],
            };
        @endphp
        <a href="{{ route('hikes.show', $hike->slug) }}" style="display:block;background:#fff;border-radius:20px;border:1px solid #e8e0d0;overflow:hidden;text-decoration:none;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 12px 40px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';" style="transition:all .2s;">
            <div style="height:4px;background:linear-gradient(90deg,#c9a84c,rgba(201,168,76,0.2));"></div>
            <div style="padding:20px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="background:#0d1e13;border-radius:10px;padding:8px 12px;text-align:center;min-width:52px;">
                        <p style="font-size:9px;text-transform:uppercase;letter-spacing:0.15em;color:#c9a84c;font-weight:700;margin:0;">{{ $hike->departs_at->format('M') }}</p>
                        <p style="font-size:22px;font-weight:700;color:#f5f0e8;line-height:1;margin:2px 0 0;">{{ $hike->departs_at->format('d') }}</p>
                    </div>
                    <div style="text-align:right;">
                        <span style="font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;background:{{ $diffColor['bg'] }};color:{{ $diffColor['text'] }};text-transform:uppercase;letter-spacing:0.1em;display:inline-block;">{{ $hike->difficulty }}</span>
                        @if($hike->is_overnight)
                        <span style="display:block;font-size:10px;font-weight:600;color:#6366f1;margin-top:4px;">{{ $hike->nights }}-night trip</span>
                        @endif
                    </div>
                </div>
                <h3 class="serif" style="font-size:20px;font-weight:700;color:#1a1a1a;margin:0 0 4px;line-height:1.3;">{{ $hike->title }}</h3>
                <p style="font-size:13px;color:#6b7280;margin:0 0 14px;">📍 {{ $hike->location }}</p>
                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:14px;border-top:1px solid #f0ece4;">
                    <div>
                        <p style="font-size:11px;color:#9ca3af;margin:0;">From</p>
                        <p style="font-size:22px;font-weight:700;color:#166534;margin:0;line-height:1.1;">R{{ number_format($hike->price, 0) }}</p>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-size:12px;margin:0;font-weight:{{ $hike->spots_remaining <= 5 ? '600' : '400' }};color:{{ $hike->spots_remaining <= 5 ? '#dc2626' : '#6b7280' }};">
                            {{ $hike->spots_remaining }} spots left
                        </p>
                        @if($hike->includes_transport)<p style="font-size:11px;color:#d97706;margin:3px 0 0;">🚌 Transport avail.</p>@endif
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</section>

{{-- ── EXPLORER LEVELS ── --}}
@php $levels = \App\Models\ExplorerLevel::orderBy('min_points')->get(); @endphp
@if($levels->isNotEmpty())
<section style="background:#0d1e13;padding:60px 24px;">
    <div style="height:1px;background:linear-gradient(90deg,transparent,rgba(201,168,76,0.3),transparent);margin-bottom:48px;"></div>
    <div style="max-width:1100px;margin:0 auto;">
        <p style="font-size:10px;text-transform:uppercase;letter-spacing:0.2em;color:#c9a84c;font-weight:700;text-align:center;margin-bottom:10px;">The Explorer Passport</p>
        <h2 class="serif" style="font-size:36px;font-weight:700;color:#f5f0e8;text-align:center;margin:0 0 40px;">Hike more. Earn more. Pay less.</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;">
            @foreach($levels as $level)
            <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:16px;padding:20px;text-align:center;">
                <div style="width:40px;height:40px;border-radius:50%;background:{{ $level->badge_color }};margin:0 auto 12px;"></div>
                <p style="font-size:14px;font-weight:700;color:#f5f0e8;margin:0 0 4px;">{{ $level->name }}</p>
                <p style="font-size:12px;color:#4a6a52;margin:0 0 8px;">{{ number_format($level->min_points) }}+ pts</p>
                @if($level->discount_percentage > 0)
                <p style="font-size:14px;font-weight:700;color:#c9a84c;margin:0;">{{ $level->discount_percentage }}% off</p>
                @else
                <p style="font-size:12px;color:#3d5545;margin:0;">Starting level</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    <div style="height:1px;background:linear-gradient(90deg,transparent,rgba(201,168,76,0.3),transparent);margin-top:48px;"></div>
</section>
@endif

{{-- ── FOOTER ── --}}
<footer style="background:#060f08;padding:32px 24px;text-align:center;">
    <p class="serif" style="font-size:20px;color:#c9a84c;font-weight:700;margin:0 0 6px;">BushXplorer</p>
    <p style="font-size:12px;color:#2d4a32;margin:0;">Adventure awaits · South Africa</p>
</footer>

</body>
</html>
