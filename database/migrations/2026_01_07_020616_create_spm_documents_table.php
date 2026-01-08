<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spm_documents', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kode_dokumen')->nullable();
            $table->enum('kategori', ['peraturan_rektor', 'dokumen_spmi', 'standar_pendidikan']);
            $table->text('deskripsi')->nullable();
            $table->string('versi')->default('1.0');
            $table->date('tanggal_terbit');
            $table->string('penanggung_jawab');
            $table->string('file_path')->nullable();
            $table->enum('status', ['aktif', 'revisi', 'tidak_berlaku'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spm_documents');
    }
};