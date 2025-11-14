<?php
// app/Models/Peserta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'peserta'; // Nama tabel di database

    protected $fillable = [
        'nama',
        'jabatan', 
        
        'kelas',
        'barcode_data'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->created_at)) {
                $model->created_at = now();
            }
            if (empty($model->updated_at)) {
                $model->updated_at = now();
            }
        });

        static::updating(function ($model) {
            $model->updated_at = now();
        });
    }

    // Jika ingin menggunakan 'id' sebagai route parameter
    public function getRouteKeyName()
    {
        return 'id';
    }
}