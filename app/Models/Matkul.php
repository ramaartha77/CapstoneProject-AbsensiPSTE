<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matkul extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_matkul';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_matkul',
        'id_akun',
        'nama_matkul',
        'sks',
        'semester',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'id_akun', 'id_akun');
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'id_matkul', 'id_matkul');
    }
    public function krs()
    {
        return $this->hasManyThrough(
            Krs::class,
            Kelas::class,
            'id_matkul', // Foreign key on kelas table...
            'id_kelas', // Foreign key on krs table...
            'id_matkul', // Local key on matkul table...
            'id_kelas' // Local key on kelas table...
        );
    }
}
