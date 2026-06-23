<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_plans', function (Blueprint $table) {
            if (! Schema::hasColumn('event_plans', 'nights')) {
                $table->unsignedTinyInteger('nights')->default(0)->after('transport_pickup_points');
            }
            if (! Schema::hasColumn('event_plans', 'accommodation_name')) {
                $table->string('accommodation_name')->nullable()->after('nights');
            }
            if (! Schema::hasColumn('event_plans', 'accommodation_cost_per_person')) {
                $table->decimal('accommodation_cost_per_person', 10, 2)->default(0)->after('accommodation_name');
            }
            if (! Schema::hasColumn('event_plans', 'what_is_included')) {
                $table->text('what_is_included')->nullable()->after('accommodation_cost_per_person');
            }
            if (! Schema::hasColumn('event_plans', 'what_to_bring')) {
                $table->text('what_to_bring')->nullable()->after('what_is_included');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_plans', function (Blueprint $table) {
            $table->dropColumn(['nights','accommodation_name','accommodation_cost_per_person','what_is_included','what_to_bring']);
        });
    }
};
