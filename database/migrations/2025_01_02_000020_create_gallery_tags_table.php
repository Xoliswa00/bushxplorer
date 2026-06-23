<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_item_id')->constrained('gallery')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->enum('tagged_by', ['self', 'admin'])->default('self');
            $table->boolean('approved')->default(true);  // admin tags auto-approved; self-tags can be moderated
            $table->timestamps();

            $table->unique(['gallery_item_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_tags');
    }
};
