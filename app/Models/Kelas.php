<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'id_matkul',
        'id_akun',
        'nama_kelas',
        'ruangan',
        'hari',
        'waktu',
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

    // Relationship with Account
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_akun', 'id_akun');
    }
}
