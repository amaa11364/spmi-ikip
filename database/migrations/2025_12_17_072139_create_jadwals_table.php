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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kegiatan');
            $table->text('deskripsi')->nullable();
            $table->string('tempat')->nullable();
            $table->time('waktu')->nullable();
            $table->string('warna', 20)->nullable()->default('#996600');
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
