<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan'; // Ganti nama tabel
    protected $fillable = [
        'program_id', // Ganti dari mata_kuliah_id
        'sesi_ke', 
        'materi', 
        'waktu_mulai', 
        'waktu_akhir', 
        'created_by'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_akhir' => 'datetime',
    ];

    // Ganti relasi mataKuliah menjadi program
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'created_by');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'kegiatan_id'); // Tambahkan foreign key
    }
}