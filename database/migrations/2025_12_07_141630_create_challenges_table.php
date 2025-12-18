<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['daily', 'weekly', 'monthly'])->default('daily');
            $table->integer('target_amount')->nullable(); // Target jumlah (kg, unit, dll)
            $table->string('target_unit')->nullable(); // kg, unit, dll
            $table->foreignId('target_category_id')->nullable()->constrained('waste_categories');
            $table->integer('points_reward'); // Poin yang didapat jika selesai
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
