<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hikes', function (Blueprint $table) {
            if (! Schema::hasColumn('hikes', 'nights')) {
                $table->unsignedTinyInteger('nights')->default(0)->after('transport_fee')
                    ->comment('0 = day trip, 1+ = overnight');
            }
            if (! Schema::hasColumn('hikes', 'accommodation_name')) {
                $table->string('accommodation_name')->nullable()->after('nights');
            }
            if (! Schema::hasColumn('hikes', 'accommodation_cost_per_person')) {
                $table->decimal('accommodation_cost_per_person', 10, 2)->default(0)->after('accommodation_name');
            }
            if (! Schema::hasColumn('hikes', 'what_is_included')) {
                $table->text('what_is_included')->nullable()->after('accommodation_cost_per_person');
            }
            if (! Schema::hasColumn('hikes', 'what_to_bring')) {
                $table->text('what_to_bring')->nullable()->after('what_is_included');
            }
        });

        // Add accommodation to expenses category — event_plans already uses JSON so no change there.
        // SQLite does not enforce enum constraints so we document it here and validate in PHP.
        // (In MySQL/PG production, run: ALTER TABLE expenses MODIFY category ENUM(...,'accommodation'))
    }

    public function down(): void
    {
        Schema::table('hikes', function (Blueprint $table) {
            $table->dropColumn([
                'nights', 'accommodation_name',
                'accommodation_cost_per_person',
                'what_is_included', 'what_to_bring',
            ]);
        });
    }
};
