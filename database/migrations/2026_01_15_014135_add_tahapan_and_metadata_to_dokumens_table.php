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
        Schema::table('dokumens', function (Blueprint $table) {
            // Tambah kolom tahapan
            if (!Schema::hasColumn('dokumens', 'tahapan')) {
                $table->string('tahapan')->nullable()->after('is_public');
            }
            
            // Tambah kolom metadata (JSON)
            if (!Schema::hasColumn('dokumens', 'metadata')) {
                $table->json('metadata')->nullable()->after('tahapan');
            }
            
            // Index untuk pencarian
            $table->index('tahapan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropColumn(['tahapan', 'metadata']);
            $table->dropIndex(['tahapan']);
        });
    }
};