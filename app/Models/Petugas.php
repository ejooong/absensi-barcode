<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Petugas extends Authenticatable
{
    use HasFactory;

    protected $table = 'petugas'; // Tambahkan ini
    protected $fillable = ['username', 'password'];
    protected $hidden = ['password', 'remember_token'];
}