<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah'; // Tambahkan ini
    protected $fillable = ['nama_materi', 'kode_materi'];

    public function jadwalSesi()
    {
        return $this->hasMany(JadwalSesi::class);
    }
}