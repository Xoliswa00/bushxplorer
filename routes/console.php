<?php

use App\Jobs\ReportHealthStatus;
use App\Mail\TripReminderMail;
use App\Models\Booking;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Send trip reminder emails 48 h before departure ───────────────────────────
Schedule::call(function () {
    $window_start = now()->addHours(47);
    $window_end = now()->addHours(49);

    Booking::with(['member.user', 'hike'])
        ->where('status', Booking::STATUS_CONFIRMED)
        ->whereHas('hike', function ($q) use ($window_start, $window_end) {
            $q->whereBetween('departs_at', [$window_start, $window_end]);
        })
        ->each(function (Booking $booking) {
            $email = $booking->member->user?->email;
            if ($email) {
                Mail::to($email)->send(new TripReminderMail($booking));
            }
        });
})->hourly()->name('trip-reminders')->withoutOverlapping();

// ── Xquisite monitoring heartbeat ── sync, not queued, so a dead queue
// worker can't mask an outage (see App\Jobs\ReportHealthStatus).
Schedule::job(new ReportHealthStatus)->everyFiveMinutes();
