<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            // Step 1 — Concept
            $table->string('title');
            $table->enum('type', ['hike', 'workshop', 'social', 'corporate', 'multi_day'])->default('hike');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->text('concept_notes')->nullable();
            $table->string('cover_color', 20)->default('#166534'); // Tailwind green-800

            // Step 2 — Logistics
            $table->string('location')->nullable();
            $table->string('trail_name')->nullable();
            $table->enum('difficulty', ['easy', 'moderate', 'hard', 'extreme'])->nullable();
            $table->dateTime('departs_at')->nullable();
            $table->dateTime('returns_at')->nullable();
            $table->string('meeting_point')->nullable();

            // Step 3 — Capacity & Transport
            $table->integer('max_capacity')->nullable();
            $table->integer('min_capacity')->nullable();
            $table->integer('points_awarded')->default(10);
            $table->boolean('includes_transport')->default(false);
            $table->decimal('transport_fee', 10, 2)->default(0);
            $table->json('transport_pickup_points')->nullable(); // draft pickup points

            // Step 4 — Financials
            $table->json('expenses')->nullable();              // [{description, category, amount}]
            $table->decimal('target_margin_pct', 5, 2)->default(20);
            $table->decimal('price', 10, 2)->nullable();       // final ticket price

            // Meta
            $table->integer('current_step')->default(1);
            $table->enum('status', ['ideation', 'logistics', 'capacity', 'financials', 'review', 'published'])->default('ideation');
            $table->foreignId('published_hike_id')->nullable()->constrained('hikes')->nullOnDelete();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_plans');
    }
};
