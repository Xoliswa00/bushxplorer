<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_ref')->unique();   // BXB-2025-00042
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->foreignId('hike_id')->constrained('hikes')->onDelete('cascade');
            $table->enum('status', [
                'draft',
                'pending_payment',
                'payment_uploaded',
                'payment_verified',
                'confirmed',
                'attended',
                'cancelled',
                'refunded',
            ])->default('draft');
            $table->integer('spots')->default(1);
            $table->decimal('amount_due', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('discount_applied', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('attended_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
