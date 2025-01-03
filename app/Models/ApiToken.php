<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasFactory;

    protected $table = 'api_tokens';
    protected $fillable = ['id_alat_absen', 'token'];

    // Relasi ke Alat
    public function alat()
    {
        return $this->belongsTo(Alat::class, 'id_alat_absen');
    }
}
