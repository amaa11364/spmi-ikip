<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->string('kegiatan'); // ubah dari nama_kegiatan ke kegiatan
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->time('waktu')->nullable();
            $table->string('tempat')->nullable(); // ubah dari lokasi ke tempat
            $table->string('penanggung_jawab')->nullable();
            $table->enum('status', ['akan_datang', 'sedang_berlangsung', 'selesai', 'dibatalkan'])->default('akan_datang');
            $table->string('kategori')->nullable();
            $table->string('warna')->nullable(); // untuk tampilan kalender
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};