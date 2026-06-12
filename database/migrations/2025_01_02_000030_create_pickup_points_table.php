<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickup_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hike_id')->constrained('hikes')->onDelete('cascade');
            $table->string('name');                            // "Sandton City P1 entrance"
            $table->string('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->time('departure_time');                    // time the bus leaves this point
            $table->integer('max_seats');                      // capacity at this stop
            $table->string('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_points');
    }
};
