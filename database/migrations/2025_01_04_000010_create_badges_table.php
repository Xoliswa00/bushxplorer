<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon', 10);
            $table->string('description');
            $table->string('criteria_type');      // hikes_attended | total_points | overnight_bookings
            $table->unsignedInteger('criteria_threshold');
            $table->string('color', 30)->default('#c9a84c');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('member_badge', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
            $table->timestamp('awarded_at');
            $table->timestamps();
            $table->unique(['member_id', 'badge_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_badge');
        Schema::dropIfExists('badges');
    }
};
