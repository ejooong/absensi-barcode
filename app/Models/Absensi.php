<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi'; // Tambahkan ini
    protected $fillable = ['peserta_id', 'jadwal_sesi_id', 'waktu_absen', 'status'];

    protected $casts = [
        'waktu_absen' => 'datetime',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function jadwalSesi()
    {
        return $this->belongsTo(JadwalSesi::class);
    }
}