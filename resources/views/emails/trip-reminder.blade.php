@extends('emails.layout')

@section('content')
<h2>48 hours, {{ $booking->member->first_name }}. The trail is calling. 🌄</h2>

<p>Your adventure to <strong>{{ $booking->hike->title }}</strong> departs in just 48 hours.
Here's everything you need for the morning.</p>

<div class="meta-box">
    <div class="meta-row">
        <span class="meta-label">Trip</span>
        <span class="meta-value">{{ $booking->hike->title }}</span>
    </div>
    <div class="meta-row">
        <span class="meta-label">Departs</span>
        <span class="meta-value highlight">{{ $booking->hike->departs_at->format('D, d M Y · H:i') }}</span>
    </div>
    @if($booking->hike->meeting_point)
    <div class="meta-row">
        <span class="meta-label">Meeting point</span>
        <span class="meta-value" style="max-width:200px; word-break:break-word;">{{ $booking->hike->meeting_point }}</span>
    </div>
    @endif
    @if($booking->hike->returns_at)
    <div class="meta-row">
        <span class="meta-label">Expected return</span>
        <span class="meta-value">{{ $booking->hike->returns_at->format('D, d M Y · H:i') }}</span>
    </div>
    @endif
    <div class="meta-row">
        <span class="meta-label">Booking ref</span>
        <span class="meta-value">{{ $booking->booking_ref }}</span>
    </div>
    @if(($booking->hike->points_awarded ?? 0) > 0)
    <div class="meta-row">
        <span class="meta-label">Points you'll earn</span>
        <span class="meta-value highlight">+{{ $booking->hike->points_awarded }} Explorer Points</span>
    </div>
    @endif
</div>

@if($booking->hike->what_to_bring)
<hr class="divider">
<p><strong>🎒 What to pack</strong></p>
<p style="white-space:pre-line; font-size:13px; line-height:1.8;">{{ $booking->hike->what_to_bring }}</p>
@endif

@if($booking->hike->what_is_included)
<hr class="divider">
<p><strong>✅ What's included</strong></p>
<p style="white-space:pre-line; font-size:13px; line-height:1.8;">{{ $booking->hike->what_is_included }}</p>
@endif

<hr class="divider">
<p style="font-size:13px; color:#78716c;">
    Get a good night's sleep. Charge your phone. Fill your water bottles.
    We can't wait to share the trail with you.
</p>

<p style="margin-bottom:20px; font-size:13px; color:#78716c;">
    — The BushXplorer team 🌿
</p>

<a href="{{ url('/member/dashboard') }}" class="btn btn-green">View Booking Details</a>
@endsection
