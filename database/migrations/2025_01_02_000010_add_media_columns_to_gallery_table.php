<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery', function (Blueprint $table) {
            $table->enum('type', ['photo', 'video'])->default('photo')->after('file_path');
            $table->unsignedBigInteger('file_size')->nullable()->after('type');   // bytes
            $table->string('mime_type', 50)->nullable()->after('file_size');
            $table->integer('duration_seconds')->nullable()->after('mime_type');  // videos only
            $table->json('social_platforms')->nullable()->after('sort_order');    // {instagram:{id,published_at}, facebook:{id,published_at}}
            $table->dateTime('social_published_at')->nullable()->after('social_platforms');
        });
    }

    public function down(): void
    {
        Schema::table('gallery', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'file_size', 'mime_type', 'duration_seconds',
                'social_platforms', 'social_published_at',
            ]);
        });
    }
};
