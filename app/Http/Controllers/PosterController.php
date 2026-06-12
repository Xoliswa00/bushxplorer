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
        $plan = EventPlan::where('created_by', Auth::id())->findOrFail($planId);
        return view('planner.poster', compact('plan'));
    }

    public function download(int $planId): \Illuminate\Http\Response
    {
        $plan = EventPlan::where('created_by', Auth::id())->findOrFail($planId);

        $pdf = Pdf::loadView('planner.poster-pdf', compact('plan'))
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = str($plan->title)->slug() . '-poster.pdf';

        return $pdf->download($filename);
    }
}
