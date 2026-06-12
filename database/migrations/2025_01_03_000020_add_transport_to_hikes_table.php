<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hikes', function (Blueprint $table) {
            if (! Schema::hasColumn('hikes', 'includes_transport')) {
                $table->boolean('includes_transport')->default(false)->after('cover_image');
            }
            if (! Schema::hasColumn('hikes', 'transport_fee')) {
                $table->decimal('transport_fee', 10, 2)->default(0)->after('includes_transport');
            }
        });
    }

    public function down(): void
    {
        Schema::table('hikes', function (Blueprint $table) {
            $table->dropColumn(['includes_transport', 'transport_fee']);
        });
    }
};
