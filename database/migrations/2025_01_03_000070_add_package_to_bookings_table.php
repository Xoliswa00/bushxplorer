<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'package')) {
                $table->string('package')->default('day')->after('status'); // day|stay|full
            }
            if (! Schema::hasColumn('bookings', 'accommodation_fee_applied')) {
                $table->decimal('accommodation_fee_applied', 10, 2)->default(0)->after('transport_fee_applied');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['package', 'accommodation_fee_applied']);
        });
    }
};
