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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom unit_kerja_id jika belum ada
            if (!Schema::hasColumn('users', 'unit_kerja_id')) {
                $table->foreignId('unit_kerja_id')->nullable()->after('avatar')
                      ->constrained('unit_kerjas')->nullOnDelete();
            }

            // Tambahkan kolom program_studi_id jika belum ada
            if (!Schema::hasColumn('users', 'program_studi_id')) {
                $table->foreignId('program_studi_id')->nullable()->after('unit_kerja_id')
                      ->constrained('program_studis')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'unit_kerja_id')) {
                $table->dropForeign(['unit_kerja_id']);
                $table->dropColumn('unit_kerja_id');
            }
            
            if (Schema::hasColumn('users', 'program_studi_id')) {
                $table->dropForeign(['program_studi_id']);
                $table->dropColumn('program_studi_id');
            }
        });
    }
};