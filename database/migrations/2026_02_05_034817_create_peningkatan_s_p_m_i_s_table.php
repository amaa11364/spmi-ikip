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
        // 1. Buat tabel peningkatan
        Schema::create('peningkatan_s_p_m_i_s', function (Blueprint $table) {
            $table->id();
            
            // Informasi Program
            $table->string('nama_program');
            $table->string('tipe_peningkatan')->default('akademik');
            $table->integer('tahun');
            $table->string('kode_peningkatan')->unique();
            
            // Status
            $table->string('status')->default('draft');
            $table->string('status_dokumen')->default('belum_valid');
            
            // Deskripsi
            $table->text('deskripsi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            
            // Target dan Realisasi
            $table->decimal('target', 10, 2)->nullable();
            $table->decimal('realisasi', 10, 2)->nullable();
            $table->decimal('anggaran', 15, 2)->nullable();
            $table->decimal('realisasi_anggaran', 15, 2)->nullable();
            
            // Waktu Pelaksanaan
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->dateTime('tanggal_review')->nullable();
            
            // Verifikasi
            $table->text('catatan_verifikasi')->nullable();
            $table->string('diperiksa_oleh')->nullable();
            
            // Foreign Keys
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas')->onDelete('set null');
            $table->foreignId('iku_id')->nullable()->constrained('ikus')->onDelete('set null');
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumens')->onDelete('set null');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('tahun');
            $table->index('status');
            $table->index('tipe_peningkatan');
            $table->index('unit_kerja_id');
            $table->index('created_at');
        });
        
        // 2. Tambah kolom peningkatan_id ke tabel dokumens (WAJIB untuk relasi)
        Schema::table('dokumens', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada (supaya aman)
            if (!Schema::hasColumn('dokumens', 'peningkatan_id')) {
                $table->foreignId('peningkatan_id')
                      ->nullable()
                      ->after('evaluasi_id')
                      ->constrained('peningkatan_s_p_m_i_s')
                      ->onDelete('set null');
                      
                // Index untuk performance
                $table->index('peningkatan_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus foreign key di dokumens terlebih dahulu
        Schema::table('dokumens', function (Blueprint $table) {
            // Cek dulu apakah kolom ada
            if (Schema::hasColumn('dokumens', 'peningkatan_id')) {
                $table->dropForeign(['peningkatan_id']);
                $table->dropIndex(['peningkatan_id']);
                $table->dropColumn('peningkatan_id');
            }
        });
        
        // Hapus tabel peningkatan
        Schema::dropIfExists('peningkatan_s_p_m_i_s');
    }
};