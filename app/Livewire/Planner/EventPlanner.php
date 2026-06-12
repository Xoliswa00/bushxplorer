<?php

namespace App\Livewire\Planner;

use App\Models\Accommodation;
use App\Models\EventPlan;
use App\Services\EventPlanService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class EventPlanner extends Component
{
    // ── Navigation ────────────────────────────────────────────────────────────
    public int $step = 1;

    // ── Step 1: Concept ───────────────────────────────────────────────────────
    public string $title        = '';
    public string $type         = 'hike';
    public string $tagline      = '';
    public string $description  = '';
    public string $conceptNotes = '';
    public string $coverColor   = '#166534';

    // ── Step 2: Logistics ─────────────────────────────────────────────────────
    public string  $location     = '';
    public string  $trailName    = '';
    public string  $difficulty   = 'moderate';
    public string  $departsAt    = '';
    public string  $returnsAt    = '';
    public string  $meetingPoint = '';

    // ── Step 3: Capacity, Transport & Accommodation ──────────────────────────
    public int    $maxCapacity                = 20;
    public int    $minCapacity                = 5;
    public int    $pointsAwarded              = 10;
    public bool   $includesTransport          = false;
    public string $transportFee               = '0';
    public array  $pickupPoints               = [];
    public bool   $includesAccommodation      = false;
    public int    $nights                     = 1;
    public string $accommodationName          = '';
    public string $accommodationCostPerPerson = '0';
    public string $whatIsIncluded             = '';
    public string $whatToBring                = '';
    public string $accommodationSearch        = '';
    public ?int   $selectedAccommodationId    = null;

    // ── Step 4: Financials ───────────────────────────────────────────────────
    public array  $expenses          = [];
    public string $targetMarginPct   = '20';
    public string $price             = '';
    public bool   $useManualPrice    = false;

    // ── Internal ─────────────────────────────────────────────────────────────
    public ?int $planId = null;

    public function mount(?int $planId = null): void
    {
        if ($planId) {
            $this->loadPlan(EventPlan::where('created_by', Auth::id())->findOrFail($planId));
        }
    }

    private function loadPlan(EventPlan $plan): void
    {
        $this->planId       = $plan->id;
        $this->step         = min($plan->current_step, 5);

        // Step 1
        $this->title        = $plan->title ?? '';
        $this->type         = $plan->type ?? 'hike';
        $this->tagline      = $plan->tagline ?? '';
        $this->description  = $plan->description ?? '';
        $this->conceptNotes = $plan->concept_notes ?? '';
        $this->coverColor   = $plan->cover_color ?? '#166534';

        // Step 2
        $this->location     = $plan->location ?? '';
        $this->trailName    = $plan->trail_name ?? '';
        $this->difficulty   = $plan->difficulty ?? 'moderate';
        $this->departsAt    = $plan->departs_at?->format('Y-m-d\TH:i') ?? '';
        $this->returnsAt    = $plan->returns_at?->format('Y-m-d\TH:i') ?? '';
        $this->meetingPoint = $plan->meeting_point ?? '';

        // Step 3
        $this->maxCapacity                = $plan->max_capacity ?? 20;
        $this->minCapacity                = $plan->min_capacity ?? 5;
        $this->pointsAwarded              = $plan->points_awarded ?? 10;
        $this->includesTransport          = $plan->includes_transport ?? false;
        $this->transportFee               = (string) ($plan->transport_fee ?? '0');
        $this->pickupPoints               = $plan->transport_pickup_points ?? [];
        $this->includesAccommodation      = ($plan->nights ?? 0) > 0;
        $this->nights                     = (int) ($plan->nights ?? 1);
        $this->accommodationName          = $plan->accommodation_name ?? '';
        $this->accommodationCostPerPerson = (string) ($plan->accommodation_cost_per_person ?? '0');
        $this->whatIsIncluded             = $plan->what_is_included ?? '';
        $this->whatToBring                = $plan->what_to_bring ?? '';
        $this->accommodationSearch        = '';
        $this->selectedAccommodationId    = null;

        // Step 4
        $this->expenses        = $plan->expenses ?? [];
        $this->targetMarginPct = (string) ($plan->target_margin_pct ?? '20');
        $this->price           = $plan->price ? (string) $plan->price : '';
        $this->useManualPrice  = (bool) $plan->price;
    }

    // ── Computed ──────────────────────────────────────────────────────────────

    #[Computed]
    public function plan(): ?EventPlan
    {
        return $this->planId ? EventPlan::find($this->planId) : null;
    }

    #[Computed]
    public function totalExpenses(): float
    {
        return collect($this->expenses)->sum(fn ($e) => (float) ($e['amount'] ?? 0));
    }

    #[Computed]
    public function breakEvenPrice(): float
    {
        $cap       = max(1, (int) $this->minCapacity);
        $transport = $this->includesTransport ? (float) $this->transportFee : 0;
        return round(($this->totalExpenses / $cap) + $transport, 2);
    }

    #[Computed]
    public function suggestedPrice(): float
    {
        $margin = (float) ($this->targetMarginPct ?: 20);
        return round($this->breakEvenPrice * (1 + $margin / 100), 2);
    }

    #[Computed]
    public function effectivePrice(): float
    {
        return $this->useManualPrice && $this->price !== ''
            ? (float) $this->price
            : $this->suggestedPrice;
    }

    #[Computed]
    public function revenueForecast(): array
    {
        $min      = max(1, (int) $this->minCapacity);
        $max      = max($min, (int) $this->maxCapacity);
        $expected = (int) round($min + ($max - $min) * 0.75);

        $calc = function (int $n): float {
            $ticket    = $this->effectivePrice * $n;
            $transport = $this->includesTransport ? (float) $this->transportFee * $n : 0;
            return round($ticket + $transport - $this->totalExpenses, 2);
        };

        return [
            'min'      => ['headcount' => $min,      'profit' => $calc($min)],
            'expected' => ['headcount' => $expected,  'profit' => $calc($expected)],
            'max'      => ['headcount' => $max,       'profit' => $calc($max)],
        ];
    }

    #[Computed]
    public function stepLabels(): array
    {
        return EventPlanService::STEPS;
    }

    #[Computed]
    public function accommodationSuggestions(): Collection
    {
        $query = Accommodation::active();

        $searchTerm = trim($this->accommodationSearch);
        $locationTerm = trim($this->location);

        if ($searchTerm !== '') {
            $query->search($searchTerm);
        } elseif ($locationTerm !== '') {
            $query->search($locationTerm);
        }

        return $query->orderBy('avg_cost_per_person')->limit(9)->get();
    }

    // ── Accommodation selection ───────────────────────────────────────────────

    public function selectAccommodation(int $id): void
    {
        $acc = Accommodation::find($id);
        if (! $acc) return;

        $this->selectedAccommodationId    = $id;
        $this->accommodationName          = $acc->name;
        $this->accommodationCostPerPerson = (string) ($acc->avg_cost_per_person ?? '0');

        if ($acc->amenities) {
            $included = collect($acc->amenities)
                ->map(fn ($a) => match ($a) {
                    'breakfast_included' => 'Breakfast included',
                    'braai'              => 'Braai facilities',
                    'pool'               => 'Swimming pool',
                    'wifi'               => 'Wi-Fi',
                    'hiking_trails'      => 'On-site hiking trails',
                    'guided_hikes'       => 'Guided hikes available',
                    'restaurant'         => 'On-site restaurant',
                    'spa'                => 'Spa & wellness',
                    'game_viewing'       => 'Game viewing',
                    'communal_kitchen'   => 'Communal kitchen',
                    'self_catering'      => 'Self-catering',
                    default              => str_replace('_', ' ', ucfirst($a)),
                })
                ->implode(', ');

            if ($included && empty(trim($this->whatIsIncluded))) {
                $this->whatIsIncluded = $included;
            }
        }

        if ($acc->group_notes && empty(trim($this->whatToBring))) {
            // group_notes go to the notes area, not to what-to-bring
        }

        unset($this->accommodationSuggestions);
    }

    public function clearAccommodationSelection(): void
    {
        $this->selectedAccommodationId = null;
        unset($this->accommodationSuggestions);
    }

    // ── Step transitions ─────────────────────────────────────────────────────

    public function nextStep(): void
    {
        $this->validateCurrentStep();
        $this->persistStep();
        $this->step++;
        unset($this->plan);
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function goToStep(int $target): void
    {
        // Only allow navigating to completed steps
        $max = $this->plan ? $this->plan->current_step : $this->step;
        if ($target <= $max) {
            $this->step = $target;
        }
    }

    // ── Expense line items ────────────────────────────────────────────────────

    public function addExpense(): void
    {
        $this->expenses[] = ['description' => '', 'category' => 'other', 'amount' => ''];
    }

    public function removeExpense(int $index): void
    {
        array_splice($this->expenses, $index, 1);
        $this->expenses = array_values($this->expenses);
        unset($this->totalExpenses, $this->breakEvenPrice, $this->suggestedPrice, $this->effectivePrice, $this->revenueForecast);
    }

    // ── Pickup point line items ───────────────────────────────────────────────

    public function addPickupPoint(): void
    {
        $this->pickupPoints[] = [
            'name'           => '',
            'address'        => '',
            'departure_time' => '',
            'max_seats'      => 10,
            'notes'          => '',
        ];
    }

    public function removePickupPoint(int $index): void
    {
        array_splice($this->pickupPoints, $index, 1);
        $this->pickupPoints = array_values($this->pickupPoints);
    }

    // ── Publish ───────────────────────────────────────────────────────────────

    public function publish(): void
    {
        $plan = $this->plan;

        if (! $plan) {
            $this->addError('publish', 'Save progress before publishing.');
            return;
        }

        $hike = app(EventPlanService::class)->publish($plan);
        unset($this->plan);

        $this->dispatch('plan-published', hikeSlug: $hike->slug, planId: $plan->id);
        $this->redirect(route('planner.poster', $plan->id));
    }

    // ── Internal ─────────────────────────────────────────────────────────────

    private function validateCurrentStep(): void
    {
        match ($this->step) {
            1 => $this->validate([
                'title'       => ['required', 'string', 'min:3', 'max:120'],
                'type'        => ['required', 'in:hike,workshop,social,corporate,multi_day'],
                'tagline'     => ['nullable', 'string', 'max:160'],
                'description' => ['nullable', 'string', 'max:2000'],
            ]),
            2 => $this->validate([
                'location'   => ['required', 'string', 'max:200'],
                'departsAt'  => ['required', 'date'],
                'returnsAt'  => ['nullable', 'date', 'after:departsAt'],
                'difficulty' => ['required', 'in:easy,moderate,hard,extreme'],
            ]),
            3 => $this->validate([
                'maxCapacity'                => ['required', 'integer', 'min:2'],
                'minCapacity'                => ['required', 'integer', 'min:1', 'lte:maxCapacity'],
                'transportFee'               => $this->includesTransport ? ['required', 'numeric', 'min:0'] : [],
                'nights'                     => $this->includesAccommodation ? ['required', 'integer', 'min:1'] : [],
                'accommodationCostPerPerson' => $this->includesAccommodation ? ['required', 'numeric', 'min:0'] : [],
            ]),
            4 => $this->validate([
                'targetMarginPct' => ['required', 'numeric', 'min:0', 'max:300'],
                'price' => $this->useManualPrice ? ['required', 'numeric', 'min:0'] : [],
                'expenses.*.description' => ['nullable', 'string', 'max:200'],
                'expenses.*.amount'      => ['nullable', 'numeric', 'min:0'],
            ]),
            default => null,
        };
    }

    private function persistStep(): void
    {
        $data = match ($this->step) {
            1 => [
                'title'         => $this->title,
                'type'          => $this->type,
                'tagline'       => $this->tagline,
                'description'   => $this->description,
                'concept_notes' => $this->conceptNotes,
                'cover_color'   => $this->coverColor,
            ],
            2 => [
                'location'      => $this->location,
                'trail_name'    => $this->trailName,
                'difficulty'    => $this->difficulty,
                'departs_at'    => $this->departsAt ?: null,
                'returns_at'    => $this->returnsAt ?: null,
                'meeting_point' => $this->meetingPoint,
            ],
            3 => [
                'max_capacity'                 => $this->maxCapacity,
                'min_capacity'                 => $this->minCapacity,
                'points_awarded'               => $this->pointsAwarded,
                'includes_transport'           => $this->includesTransport,
                'transport_fee'                => $this->includesTransport ? $this->transportFee : 0,
                'transport_pickup_points'      => $this->includesTransport ? $this->pickupPoints : [],
                'nights'                       => $this->includesAccommodation ? max(1, $this->nights) : 0,
                'accommodation_name'           => $this->includesAccommodation ? $this->accommodationName : null,
                'accommodation_cost_per_person'=> $this->includesAccommodation ? $this->accommodationCostPerPerson : 0,
                'what_is_included'             => $this->whatIsIncluded ?: null,
                'what_to_bring'                => $this->whatToBring ?: null,
            ],
            4 => [
                'expenses'          => $this->expenses,
                'target_margin_pct' => $this->targetMarginPct,
                'price'             => $this->useManualPrice && $this->price !== '' ? $this->price : $this->suggestedPrice,
            ],
            default => [],
        };

        if (empty($data)) return;

        $service = app(EventPlanService::class);

        if ($this->planId) {
            $plan = EventPlan::findOrFail($this->planId);
            $service->saveStep($plan, $this->step, $data);
        } else {
            $plan = EventPlan::create(array_merge($data, [
                'created_by'  => Auth::id(),
                'current_step' => 2,
                'status'      => 'ideation',
            ]));
            $this->planId = $plan->id;
        }

        unset($this->plan);
    }

    public function render()
    {
        return view('livewire.planner.event-planner')
            ->layout('components.layouts.app');
    }
}
