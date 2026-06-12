<?php

use App\Livewire\Booking\BookingConfirmation;
use App\Livewire\Booking\BookingForm;
use App\Livewire\Booking\PaymentUpload;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->prefix('hikes')->name('hikes.')->group(function () {
    Route::get('/', fn() => view('hikes.index'))->name('index');
    Route::get('/{hike:slug}', fn(\App\Models\Hike $hike) => view('hikes.show', compact('hike')))->name('show');
});

Route::middleware(['auth'])->prefix('booking')->name('booking.')->group(function () {
    Route::get('/hike/{hike:id}', BookingForm::class)->name('form');
    Route::get('/{bookingRef}/payment', PaymentUpload::class)->name('payment');
    Route::get('/{bookingRef}/confirmation', BookingConfirmation::class)->name('confirmation');
});
