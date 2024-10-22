<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $table = 't_alat_absen';
    protected $primaryKey = 'id_alat_absen';
    protected $fillable = ['nama_alat', 'ruangan'];

    // Relasi ke Pertemuan
    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'id_alat_absen');
    }
}
