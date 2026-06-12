<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hikes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('trail_name')->nullable();
            $table->enum('difficulty', ['easy', 'moderate', 'hard', 'extreme'])->default('moderate');
            $table->decimal('distance_km', 5, 2)->nullable();
            $table->integer('elevation_gain_m')->nullable();
            $table->dateTime('departs_at');
            $table->dateTime('returns_at')->nullable();
            $table->string('meeting_point')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('max_capacity')->default(20);
            $table->integer('min_capacity')->default(5);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->integer('points_awarded')->default(10);
            $table->string('cover_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hikes');
    }
};
