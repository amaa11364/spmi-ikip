<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluasiSpmsTable extends Migration
{
    public function up()
    {
        Schema::create('evaluasi_spms', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komponen');
            $table->string('tipe_evaluasi'); // internal, eksternal, berkala, khusus
            $table->integer('tahun');
            $table->string('periode'); // Semester I, Semester II, Triwulan I-IV, Tahunan
            $table->string('status')->default('draft'); // draft, proses, selesai, ditunda
            $table->string('status_dokumen')->default('belum_valid'); // valid, belum_valid, dalam_review
            $table->text('deskripsi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->string('kode_evaluasi')->unique();
            $table->foreignId('unit_kerja_id')->nullable()->constrained('unit_kerja');
            $table->foreignId('iku_id')->nullable()->constrained('ikus');
            $table->foreignId('dokumen_id')->nullable()->constrained('dokumens');
            $table->string('file_path')->nullable();
            $table->dateTime('tanggal_evaluasi')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->text('hasil_evaluasi')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->string('diperiksa_oleh')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluasi_spms');
    }
}