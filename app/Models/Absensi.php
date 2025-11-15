<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $fillable = [
        'peserta_id', 
        'kegiatan_id', // Ganti dari jadwal_sesi_id
        'waktu_absen', 
        'status'
    ];

    protected $casts = [
        'waktu_absen' => 'datetime',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    // Ganti relasi jadwalSesi menjadi kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }
}