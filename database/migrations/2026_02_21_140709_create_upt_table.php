<?php
// database/migrations/2024_01_01_000001_create_upt_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('upt', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('singkatan')->nullable();
            $table->string('deskripsi');
            $table->string('ikon')->default('fa-building');
            $table->string('warna')->default('#8B5E3C');
            $table->string('kepala_upt')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->integer('jumlah_staff')->default(0);
            $table->integer('jumlah_program')->default(0);
            $table->string('status')->default('aktif');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('upt');
    }
};