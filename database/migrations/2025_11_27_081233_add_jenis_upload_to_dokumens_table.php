<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisUploadToDokumensTable extends Migration
{
    public function up()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->enum('jenis_upload', ['file', 'link'])->default('file')->after('file_extension');
        });
    }

    public function down()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropColumn('jenis_upload');
        });
    }
}