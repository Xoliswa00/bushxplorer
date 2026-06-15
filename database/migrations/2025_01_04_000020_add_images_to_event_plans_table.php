<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_plans', function (Blueprint $table) {
            if (! Schema::hasColumn('event_plans', 'cover_image_url')) {
                $table->string('cover_image_url')->nullable()->after('cover_color');
            }
            if (! Schema::hasColumn('event_plans', 'scene_image_urls')) {
                $table->json('scene_image_urls')->nullable()->after('cover_image_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_plans', function (Blueprint $table) {
            $table->dropColumn(['cover_image_url', 'scene_image_urls']);
        });
    }
};
