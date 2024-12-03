<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    use HasFactory;

    protected $primaryKey = ['id_akun', 'id_kelas']; // Composite key
    public $incrementing = false; // Since we're using composite key

    protected $fillable = [
        'id_akun',
        'id_kelas',  // Changed from id_matkul to id_kelas
    ];

    protected $table = 'krs';

    // Relasi ke Akun
    public function akun()
    {
        return $this->belongsTo(Account::class, 'id_akun');
    }

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
}
