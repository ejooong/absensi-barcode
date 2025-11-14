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
    Schema::create('absensi', function (Blueprint $table) {
        $table->id();
        $table->foreignId('peserta_id')->constrained('peserta');
        $table->foreignId('jadwal_sesi_id')->constrained('jadwal_sesi');
        $table->datetime('waktu_absen');
        $table->enum('status', ['hadir', 'terlambat'])->default('hadir');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
