<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');        // lodge, camp, guesthouse, hostel, resort, farm, backpackers
            $table->string('region');      // e.g. Magaliesberg, Drakensberg, Cederberg
            $table->string('location')->nullable();  // full address / area description
            $table->decimal('avg_cost_per_person', 10, 2)->nullable();
            $table->unsignedSmallInteger('max_guests')->nullable();
            $table->json('amenities')->nullable();   // ['braai', 'pool', 'wifi', 'kitchen', 'guided_hikes']
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('booking_contact')->nullable();
            $table->text('group_notes')->nullable();  // hiking-group specific notes
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
