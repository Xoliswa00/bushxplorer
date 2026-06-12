<?php

namespace App\Services;

use App\Models\EventPlan;
use App\Models\Hike;
use App\Models\PickupPoint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventPlanService
{
    /** Step labels for UI progress tracking */
    public const STEPS = [
        1 => 'Concept',
        2 => 'Logistics',
        3 => 'Capacity & Transport',
        4 => 'Financials',
        5 => 'Review',
    ];

    /** Status that maps to each completed step */
    private const STEP_STATUS = [
        1 => 'ideation',
        2 => 'logistics',
        3 => 'capacity',
        4 => 'financials',
        5 => 'review',
    ];

    /**
     * Save progress for a step and advance the plan's current_step.
     */
    public function saveStep(EventPlan $plan, int $step, array $data): EventPlan
    {
        $data['current_step'] = max($plan->current_step, $step + 1);
        $data['status']       = self::STEP_STATUS[$step] ?? $plan->status;

        $plan->update($data);
        return $plan->refresh();
    }

    /**
     * Publish the plan: create a Hike (+ PickupPoints), mark plan as published.
     */
    public function publish(EventPlan $plan): Hike
    {
        return DB::transaction(function () use ($plan) {
            $slug = Str::slug($plan->title);
            $i    = 1;
            while (Hike::where('slug', $slug)->exists()) {
                $slug = Str::slug($plan->title) . '-' . $i++;
            }

            $hike = Hike::create([
                'title'              => $plan->title,
                'slug'               => $slug,
                'description'        => $plan->description,
                'location'           => $plan->location,
                'trail_name'         => $plan->trail_name,
                'difficulty'         => $plan->difficulty ?? 'moderate',
                'departs_at'         => $plan->departs_at,
                'returns_at'         => $plan->returns_at,
                'meeting_point'      => $plan->meeting_point,
                'price'              => $plan->price ?? $plan->suggestedPrice(),
                'max_capacity'       => $plan->max_capacity,
                'min_capacity'       => $plan->min_capacity ?? 5,
                'is_published'       => true,
                'points_awarded'     => $plan->points_awarded ?? 10,
                'includes_transport' => $plan->includes_transport,
                'transport_fee'      => $plan->transport_fee,
            ]);

            // Create pickup points from the draft JSON
            foreach ($plan->transport_pickup_points ?? [] as $idx => $point) {
                PickupPoint::create([
                    'hike_id'        => $hike->id,
                    'name'           => $point['name'],
                    'address'        => $point['address'] ?? $point['name'],
                    'latitude'       => $point['latitude'] ?? null,
                    'longitude'      => $point['longitude'] ?? null,
                    'departure_time' => $point['departure_time'],
                    'max_seats'      => $point['max_seats'],
                    'notes'          => $point['notes'] ?? null,
                    'sort_order'     => $idx,
                ]);
            }

            $plan->update([
                'status'            => 'published',
                'published_hike_id' => $hike->id,
            ]);

            return $hike;
        });
    }
}
