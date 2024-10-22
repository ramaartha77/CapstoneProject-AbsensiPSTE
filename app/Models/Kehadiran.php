<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    public $timestamps = false;

    protected $table = 'kehadiran';
    protected $primaryKey = 'id_kehadiran';
    protected $fillable = ['id_akun', 'id_pertemuan', 'id_alat_absen', 'status', 'waktu_absen'];

    // Relasi ke Akun
    public function akun()
    {
        return $this->belongsTo(Account::class, 'id_akun');
    }

    // Relasi ke Pertemuan
    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'id_pertemuan');
    }
    // Relasi ke Alat
    public function alatAbsen()
    {
        return $this->belongsTo(Alat::class, 'id_alat_absen');
    }
}
