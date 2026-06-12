<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('explorer_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->integer('points');                         // positive = credit, negative = debit
            $table->enum('type', ['credit', 'debit']);
            $table->string('reason');                          // hike_attended, referral, bonus, redemption
            $table->morphs('pointable');                       // polymorphic: Booking, Order, etc.
            $table->integer('running_balance')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('explorer_points');
    }
};
