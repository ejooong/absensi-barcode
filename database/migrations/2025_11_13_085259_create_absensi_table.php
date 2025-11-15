<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hanya update foreign key constraint, tidak rename column
        Schema::table('absensi', function (Blueprint $table) {
            // Drop foreign key constraint lama
            $table->dropForeign(['jadwal_sesi_id']);
            
            // Update foreign key ke tabel kegiatan
            $table->foreign('jadwal_sesi_id')
                  ->references('id')
                  ->on('kegiatan') // Ganti ke tabel kegiatan
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            // Drop foreign key constraint baru
            $table->dropForeign(['jadwal_sesi_id']);
            
            // Kembalikan ke tabel jadwal_sesi
            $table->foreign('jadwal_sesi_id')
                  ->references('id')
                  ->on('jadwal_sesi')
                  ->onDelete('cascade');
        });
    }
};