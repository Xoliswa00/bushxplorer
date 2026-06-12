<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waitlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hike_id')->constrained('hikes')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->integer('position')->default(0);
            $table->boolean('notified')->default(false);
            $table->dateTime('notified_at')->nullable();
            $table->timestamps();

            $table->unique(['hike_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waitlist');
    }
};
