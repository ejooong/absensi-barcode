<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Rename table
        Schema::rename('jadwal_sesi', 'kegiatan');
        
        // Update foreign key constraints in absensi table
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['jadwal_sesi_id']);
            $table->renameColumn('jadwal_sesi_id', 'kegiatan_id');
            $table->foreign('kegiatan_id')->references('id')->on('kegiatan')->onDelete('cascade');
        });
        
        // Update foreign key in jadwal_sesi (now kegiatan) table
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->foreign('program_id')->references('id')->on('program')->onDelete('cascade');
        });
    }

    public function down()
    {
        // Revert foreign key in kegiatan table
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
        });
        
        // Revert foreign key in absensi table
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['kegiatan_id']);
            $table->renameColumn('kegiatan_id', 'jadwal_sesi_id');
            $table->foreign('jadwal_sesi_id')->references('id')->on('jadwal_sesi')->onDelete('cascade');
        });
        
        // Revert table name
        Schema::rename('kegiatan', 'jadwal_sesi');
    }
};