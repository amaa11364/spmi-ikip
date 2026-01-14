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
        Schema::create('spm_penetapan', function (Blueprint $table) {
            $table->id();
            
            // Kolom dasar
            $table->string('kode_penetapan')->nullable();
            $table->string('nama_komponen');
            $table->string('tipe_penetapan')->default('pengelolaan');
            $table->integer('tahun');
            
            // Status
            $table->enum('status', ['aktif', 'nonaktif', 'revisi'])->default('aktif');
            $table->enum('status_dokumen', ['valid', 'belum_valid', 'dalam_review'])->default('belum_valid');
            
            // Informasi
            $table->text('deskripsi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('file_path')->nullable();
            
            // Tanggal
            $table->date('tanggal_penetapan')->nullable();
            $table->date('tanggal_review')->nullable();
            
            // Relasi (pastikan tabel yang direferensi sudah ada)
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas')->onDelete('set null');
            $table->foreignId('iku_id')->nullable()->constrained('ikus')->onDelete('set null');
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumen')->onDelete('set null');
            
            // Verifikasi
            $table->text('catatan_verifikasi')->nullable();
            $table->string('diperiksa_oleh')->nullable();
            
            // Soft delete & timestamp
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes untuk performa
            $table->index(['tipe_penetapan', 'status']);
            $table->index(['tahun', 'status_dokumen']);
            $table->index('kode_penetapan');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spm_penetapan');
    }
};