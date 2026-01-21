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
        Schema::create('penetapan_s_p_m_s', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komponen');
            $table->enum('tipe_penetapan', [
                'pengelolaan', 
                'organisasi', 
                'pelaksanaan', 
                'evaluasi', 
                'pengendalian', 
                'peningkatan'
            ]);
            $table->integer('tahun');
            $table->enum('status', ['aktif', 'nonaktif', 'revisi'])->default('aktif');
            $table->enum('status_dokumen', ['valid', 'belum_valid', 'dalam_review'])->default('belum_valid');
            $table->text('deskripsi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('kode_penetapan')->unique();
            
            // Foreign keys
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas')->onDelete('set null');
            $table->foreignId('iku_id')->nullable()->constrained('ikus')->onDelete('set null');
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumens')->onDelete('set null');
            
            // File info
            $table->string('file_path')->nullable();
            
            // Timestamps
            $table->dateTime('tanggal_penetapan')->nullable();
            $table->dateTime('tanggal_review')->nullable();
            
            // Additional fields
            $table->text('catatan_verifikasi')->nullable();
            $table->string('diperiksa_oleh')->nullable();
            
            // Soft deletes
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index('tipe_penetapan');
            $table->index('tahun');
            $table->index('status');
            $table->index('status_dokumen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penetapan_s_p_m_s');
    }
};