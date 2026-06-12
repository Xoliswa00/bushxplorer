<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hike_id')->nullable()->constrained('hikes')->nullOnDelete();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->enum('category', ['transport', 'permits', 'equipment', 'refreshments', 'marketing', 'other'])->default('other');
            $table->date('expense_date');
            $table->string('receipt_path')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
