<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spm_implementations', function (Blueprint $table) {
            $table->id();
            $table->string('kegiatan');
            $table->text('deskripsi')->nullable();
            $table->string('unit_kerja');
            $table->enum('status', ['berjalan', 'selesai', 'tertunda'])->default('berjalan');
            $table->integer('progress')->default(0);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spm_implementations');
    }
};