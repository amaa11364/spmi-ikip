<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('beritas', function (Blueprint $table) {
        $table->string('link')->nullable()->after('isi'); // Untuk link eksternal
        $table->string('ringkasan')->nullable()->after('judul'); // Untuk excerpt
        $table->string('gambar_url')->nullable()->after('gambar'); // Untuk URL lengkap
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            //
        });
    }
};
