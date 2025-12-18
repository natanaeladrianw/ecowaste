<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migration ini tidak diperlukan lagi karena 
        // create_articles_table dan create_tips_table sudah menggunakan user_id
        // Tapi kita tetap perlu cek jika ada data lama yang perlu diupdate
        if (Schema::hasColumn('articles', 'admin_id')) {
            // Jika masih ada kolom admin_id, rename ke user_id
            Schema::table('articles', function (Blueprint $table) {
                $table->renameColumn('admin_id', 'user_id');
            });
        }

        if (Schema::hasColumn('tips', 'admin_id')) {
            // Jika masih ada kolom admin_id, rename ke user_id
            Schema::table('tips', function (Blueprint $table) {
                $table->renameColumn('admin_id', 'user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert jika diperlukan
        if (Schema::hasColumn('articles', 'user_id')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->renameColumn('user_id', 'admin_id');
            });
        }

        if (Schema::hasColumn('tips', 'user_id')) {
            Schema::table('tips', function (Blueprint $table) {
                $table->renameColumn('user_id', 'admin_id');
            });
        }
    }
};
