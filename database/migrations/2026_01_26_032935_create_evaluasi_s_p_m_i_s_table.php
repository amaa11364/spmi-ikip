<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluasi_s_p_m_i_s', function (Blueprint $table) {
            $table->id();
            $table->string('nama_evaluasi');
            $table->string('tipe_evaluasi'); // ami, edom, evaluasi_layanan, evaluasi_kinerja
            $table->integer('tahun');
            $table->string('periode')->nullable();
            $table->string('status'); // aktif, nonaktif, selesai, berjalan
            $table->string('status_dokumen')->default('belum_valid'); // valid, belum_valid, dalam_review
            $table->text('deskripsi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('kode_evaluasi')->unique();
            
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerjas');
            $table->foreignId('iku_id')->nullable()->constrained('ikus');
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumens');
            
            $table->dateTime('tanggal_evaluasi')->nullable();
            $table->dateTime('tanggal_review')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->string('diperiksa_oleh')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluasi_s_p_m_i_s');
    }
};