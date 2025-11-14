<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalSesi extends Model
{
    use HasFactory;

    protected $table = 'jadwal_sesi'; // Tambahkan ini
    protected $fillable = [
        'mata_kuliah_id', 'sesi_ke', 'materi', 
        'waktu_mulai', 'waktu_akhir', 'created_by'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_akhir' => 'datetime',
    ];

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'created_by');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
}