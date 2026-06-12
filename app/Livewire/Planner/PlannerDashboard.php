<?php

namespace App\Livewire\Planner;

use App\Models\EventPlan;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PlannerDashboard extends Component
{
    #[Computed]
    public function plans()
    {
        return EventPlan::forUser(Auth::id())
            ->with('publishedHike')
            ->latest()
            ->get();
    }

    public function deletePlan(int $id): void
    {
        EventPlan::where('created_by', Auth::id())->findOrFail($id)->delete();
        unset($this->plans);
    }

    public function render()
    {
        return view('livewire.planner.planner-dashboard')
            ->layout('components.layouts.app');
    }
}
