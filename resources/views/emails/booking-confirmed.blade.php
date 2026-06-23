@extends('emails.layout')

@section('content')
<h2>You're on the trail, {{ $booking->member->first_name }}! 🎉</h2>

<p>Your booking for <strong>{{ $booking->hike->title }}</strong> is confirmed.
Pack your boots — this one's going to be special.</p>

<div class="meta-box">
    <div class="meta-row">
        <span class="meta-label">Booking ref</span>
        <span class="meta-value">{{ $booking->booking_ref }}</span>
    </div>
    <div class="meta-row">
        <span class="meta-label">Trip</span>
        <span class="meta-value">{{ $booking->hike->title }}</span>
    </div>
    <div class="meta-row">
        <span class="meta-label">Departs</span>
        <span class="meta-value">{{ $booking->hike->departs_at->format('D, d M Y · H:i') }}</span>
    </div>
    @if($booking->hike->meeting_point)
    <div class="meta-row">
        <span class="meta-label">Meeting point</span>
        <span class="meta-value" style="max-width:200px; word-break:break-word;">{{ $booking->hike->meeting_point }}</span>
    </div>
    @endif
    <div class="meta-row">
        <span class="meta-label">Package</span>
        <span class="meta-value" style="text-transform:capitalize;">{{ $booking->package }}</span>
    </div>
    <div class="meta-row">
        <span class="meta-label">Amount paid</span>
        <span class="meta-value highlight">R{{ number_format($booking->amount_due, 2) }}</span>
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
<p><strong>What to bring</strong></p>
<p style="white-space:pre-line; font-size:13px;">{{ $booking->hike->what_to_bring }}</p>
@endif

@if($booking->hike->what_is_included)
<hr class="divider">
<p><strong>What's included</strong></p>
<p style="white-space:pre-line; font-size:13px;">{{ $booking->hike->what_is_included }}</p>
@endif

<hr class="divider">
<p style="margin-bottom:20px;">Questions about your booking? We've got you covered.</p>
<a href="{{ url('/member/dashboard') }}" class="btn btn-green">View My Dashboard</a>
@endsection
