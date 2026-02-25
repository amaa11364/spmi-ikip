<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            // Kolom verifikasi yang hilang
            if (!Schema::hasColumn('dokumens', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('dokumens', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->constrained('users')->after('verified_at');
            }
            
            if (!Schema::hasColumn('dokumens', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('verified_by');
            }
            
            // Kolom lain yang mungkin hilang
            if (!Schema::hasColumn('dokumens', 'prodi_id')) {
                $table->foreignId('prodi_id')->nullable()->constrained('prodis')->after('iku_id');
            }
            
            if (!Schema::hasColumn('dokumens', 'revision_deadline')) {
                $table->timestamp('revision_deadline')->nullable()->after('rejection_reason');
            }
        });
    }

    public function down()
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'verified_at',
                'verified_by',
                'rejection_reason',
                'prodi_id',
                'revision_deadline'
            ]);
        });
    }
};