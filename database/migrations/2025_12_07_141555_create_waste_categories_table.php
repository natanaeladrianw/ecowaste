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
        Schema::create('waste_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Organik, Anorganik, B3, Recycle
            $table->text('description')->nullable();
            $table->integer('points_per_kg')->default(0); // Poin per kg
            $table->string('color')->nullable(); // Warna untuk UI
            $table->string('icon')->nullable(); // Icon
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_categories');
    }
};
