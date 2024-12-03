<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    protected $table = 'pertemuans';
    protected $primaryKey = 'id_pertemuan';

    protected $fillable = ['id_kelas', 'id_alat_absen', 'nama_pertemuan', 'tgl_pertemuan', 'materi', 'aktivasi_absen'];

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    // Relasi ke kehadiran
    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'id_pertemuan');
    }

    // Relasi ke alat absen
    public function alatAbsen()
    {
        return $this->belongsTo(Alat::class, 'id_alat_absen');
    }
}
