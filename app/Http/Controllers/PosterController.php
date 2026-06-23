<?php

namespace App\Http\Controllers;

use App\Models\EventPlan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosterController extends Controller
{
    public function show(Request $request, int $planId): \Illuminate\View\View
    {
        $plan    = EventPlan::where('created_by', Auth::id())->findOrFail($planId);
        $joiners = $this->joiners($plan);
        return view('planner.poster', compact('plan', 'joiners'));
    }

    public function download(int $planId): \Illuminate\Http\Response
    {
        $plan    = EventPlan::where('created_by', Auth::id())->findOrFail($planId);
        $joiners = $this->joiners($plan);

        $pdf = Pdf::loadView('planner.poster-pdf', compact('plan', 'joiners'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'        => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'    => true,
            ]);

        $filename = str($plan->title)->slug() . '-poster.pdf';

        return $pdf->download($filename);
    }

    private function joiners(EventPlan $plan): \Illuminate\Support\Collection
    {
        if (! $plan->publishedHike) {
            return collect();
        }

        return $plan->publishedHike
            ->bookings()
            ->with('member.explorerLevel')
            ->whereIn('status', ['confirmed', 'attended', 'payment_verified', 'payment_uploaded'])
            ->limit(12)
            ->get()
            ->map(fn ($b) => $b->member)
            ->unique('id')
            ->values();
    }
}
