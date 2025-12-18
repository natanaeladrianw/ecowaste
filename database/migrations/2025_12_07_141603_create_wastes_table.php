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
        Schema::create('wastes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('waste_categories')->onDelete('cascade');
            $table->string('type'); // Jenis sampah (Plastik, Kertas, dll)
            $table->decimal('amount', 10, 2); // Jumlah
            $table->string('unit')->default('kg'); // kg, gram, unit, liter
            $table->date('date'); // Tanggal input
            $table->time('time')->nullable(); // Waktu input
            $table->text('description')->nullable();
            $table->string('photo')->nullable(); // Path foto
            $table->integer('points_earned')->default(0); // Poin yang didapat
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wastes');
    }
};
