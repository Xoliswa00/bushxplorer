@extends('emails.layout')

@section('content')
<h2>Payment proof needs attention</h2>

<p>Hi {{ $booking->member->first_name }}, we weren't able to verify your payment proof for
<strong>{{ $booking->booking_ref }}</strong>. Your spot is still reserved — please re-upload
a clear proof of payment to secure it.</p>

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
        <span class="meta-label">Amount due</span>
        <span class="meta-value highlight">R{{ number_format($booking->amount_due, 2) }}</span>
    </div>
    <div class="meta-row">
        <span class="meta-label">Reason</span>
        <span class="meta-value" style="max-width:200px; word-break:break-word; color:#b91c1c;">{{ $reason }}</span>
    </div>
</div>

<p><strong>What to do next:</strong></p>
<ol style="font-size:14px; line-height:1.8; color:#44403c; padding-left:20px; margin:0 0 20px;">
    <li>Log in to your dashboard</li>
    <li>Find your booking for <strong>{{ $booking->hike->title }}</strong></li>
    <li>Upload a clear screenshot or PDF of your EFT payment confirmation</li>
</ol>

<p style="margin-bottom:20px;">Make sure the proof clearly shows the payment amount, date, and your reference number.</p>

<a href="{{ url('/booking/' . $booking->booking_ref . '/payment') }}" class="btn btn-amber">Re-upload Payment Proof</a>

<p style="margin-top:20px; font-size:13px; color:#78716c;">
    If you've already paid and believe this is an error, please reply to this email with your bank-stamped statement and we'll sort it out quickly.
</p>
@endsection
