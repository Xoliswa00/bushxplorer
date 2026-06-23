<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hikes', function (Blueprint $table) {
            $table->boolean('includes_transport')->default(false)->after('cover_image');
            $table->decimal('transport_fee', 10, 2)->default(0)->after('includes_transport');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('pickup_point_id')
                ->nullable()
                ->after('hike_id')
                ->constrained('pickup_points')
                ->nullOnDelete();
            $table->decimal('transport_fee_applied', 10, 2)->default(0)->after('discount_applied');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['pickup_point_id']);
            $table->dropColumn(['pickup_point_id', 'transport_fee_applied']);
        });

        Schema::table('hikes', function (Blueprint $table) {
            $table->dropColumn(['includes_transport', 'transport_fee']);
        });
    }
};
