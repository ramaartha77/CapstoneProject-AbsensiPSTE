<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'id_matkul',
        'id_akun',
        'nama_kelas',
        'id_ruangan',
        'hari',
        'waktu',
        'id_smt',
        'thn_smt'
    ];

    protected $casts = [
        'hari' => 'string',
    ];



    // Relationship with Matkul
    public function matkul(): BelongsTo
    {
        return $this->belongsTo(Matkul::class, 'id_matkul', 'id_matkul');
    }

    // Relationship with Account (Dosen)
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_akun', 'id_akun');
    }

    // Relationship with Ruangan
    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }

    // Relationship with Semester
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Smt::class, 'id_smt', 'id_smt');
    }

    // Relationship to Pertemuan
    public function pertemuan()
    {
        return $this->hasMany(Pertemuan::class, 'id_kelas', 'id_kelas');
    }



    // Relationship to KRS
    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class, 'id_kelas', 'id_kelas');
    }

    // Relationship to Mahasiswa via KRS
    public function mahasiswa(): HasManyThrough
    {
        return $this->hasManyThrough(
            Account::class,      // Target model (mahasiswa)
            Krs::class,          // Intermediate model (KRS)
            'id_kelas',          // Foreign key on KRS (class ID)
            'id_akun',           // Foreign key on Account (user ID of mahasiswa)
            'id_kelas',          // Local key on Kelas (class ID)
            'id_akun'            // Local key on KRS (user ID of mahasiswa)
        );
    }
}
