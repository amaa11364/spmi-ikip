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
        Schema::create('pelaksanaan_spmi', function (Blueprint $table) {
            $table->id();
            
            // Informasi dasar
            $table->string('nama_kegiatan');
            $table->string('kode_pelaksanaan')->unique();
            $table->integer('tahun');
            
            // Status
            $table->enum('status', ['aktif', 'nonaktif', 'revisi'])->default('aktif');
            $table->enum('status_dokumen', ['valid', 'belum_valid', 'dalam_review'])->default('belum_valid');
            
            // Deskripsi
            $table->text('deskripsi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            
            // Relasi
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas')->nullOnDelete();
            $table->foreignId('iku_id')->nullable()->constrained('ikus')->nullOnDelete();
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumens')->nullOnDelete();
            
            // Metadata upload
            $table->string('file_path')->nullable();
            $table->timestamp('tanggal_pelaksanaan')->nullable();
            $table->timestamp('tanggal_review')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->string('diperiksa_oleh')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('kode_pelaksanaan');
            $table->index('tahun');
            $table->index('status');
            $table->index('status_dokumen');
            $table->index('unit_kerja_id');
            $table->index('iku_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaksanaan_spmi');
    }
};