<?php
// database/migrations/[timestamp]_create_akreditasi_s_p_m_s_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('akreditasi_s_p_m_s', function (Blueprint $table) {
            $table->id();
            $table->string('judul_akreditasi');
            $table->string('jenis_akreditasi'); // institusi, program studi, dll
            $table->string('lembaga_akreditasi'); // BAN-PT, LAM, dll
            $table->integer('tahun');
            $table->date('tanggal_akreditasi')->nullable();
            $table->date('tanggal_berlaku')->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->string('peringkat')->nullable(); // A, B, C, Unggul, Baik, dll
            $table->string('skor')->nullable();
            $table->string('status'); // aktif, berjalan, selesai, tidak_akreditasi
            $table->string('status_dokumen')->default('belum_valid'); // valid, belum_valid, dalam_review
            $table->text('deskripsi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('no_sertifikat')->nullable();
            $table->string('file_sertifikat')->nullable();
            $table->string('kode_akreditasi')->unique();
            
            // Foreign keys
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas')->onDelete('set null');
            $table->foreignId('iku_id')->nullable()->constrained('ikus')->onDelete('set null');
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumens')->onDelete('set null');
            
            // Metadata
            $table->text('catatan_verifikasi')->nullable();
            $table->string('diperiksa_oleh')->nullable();
            $table->timestamp('tanggal_review')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index('jenis_akreditasi');
            $table->index('tahun');
            $table->index('status');
            $table->index('peringkat');
            $table->index('kode_akreditasi');
        });
    }

    public function down()
    {
        Schema::dropIfExists('akreditasi_s_p_m_s');
    }
};