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
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points'); // Jumlah poin
            $table->string('source'); // waste, challenge, reward, etc
            $table->foreignId('source_id')->nullable(); // ID dari source (waste_id, challenge_id, dll)
            $table->text('description')->nullable();
            $table->enum('type', ['earned', 'spent'])->default('earned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
