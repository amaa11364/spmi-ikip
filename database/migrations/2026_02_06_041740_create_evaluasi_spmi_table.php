<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluasi_spmi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_evaluasi');
            $table->string('tipe_evaluasi')->default('ami'); // ami, edom, evaluasi_layanan, evaluasi_kinerja
            $table->integer('tahun');
            $table->string('periode')->nullable();
            $table->string('status')->default('aktif'); // aktif, nonaktif, selesai, berjalan
            $table->string('status_dokumen')->default('belum_valid'); // valid, belum_valid, dalam_review
            $table->text('deskripsi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('kode_evaluasi')->unique();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas')->nullOnDelete();
            $table->foreignId('iku_id')->nullable()->constrained('ikus')->nullOnDelete();
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumens')->nullOnDelete();
            $table->text('hasil_evaluasi')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->date('target_waktu')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->string('diperiksa_oleh')->nullable();
            $table->timestamp('tanggal_evaluasi')->nullable();
            $table->timestamp('tanggal_review')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['tipe_evaluasi', 'tahun']);
            $table->index(['status', 'status_dokumen']);
            $table->index('kode_evaluasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluasi_spmi');
    }
};