<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengendalian_s_p_m_i_s', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tindakan');
            $table->string('sumber_evaluasi')->nullable();
            $table->text('deskripsi_masalah');
            $table->text('tindakan_perbaikan');
            $table->string('penanggung_jawab');
            $table->date('target_waktu');
            $table->enum('status_pelaksanaan', ['rencana', 'berjalan', 'selesai', 'terverifikasi', 'tertunda'])->default('rencana');
            $table->integer('progress')->default(0);
            $table->string('hasil_verifikasi')->nullable();
            $table->integer('tahun');
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas')->nullOnDelete();
            $table->foreignId('iku_id')->nullable()->constrained('ikus')->nullOnDelete();
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumens')->nullOnDelete();
            $table->enum('status_dokumen', ['valid', 'belum_valid', 'dalam_review'])->default('belum_valid');
            $table->text('catatan')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengendalian_s_p_m_i_s');
    }
};