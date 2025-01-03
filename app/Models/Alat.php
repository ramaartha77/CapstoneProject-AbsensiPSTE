<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat_absen';
    protected $primaryKey = 'id_alat_absen';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id_alat_absen', 'nama_alat', 'ruangan'];

    protected static function booted()
    {

        static::created(function ($alat) {

            $existingToken = DB::table('api_tokens')
                ->where('id_alat_absen', $alat->id_alat_absen)
                ->first();

            if (!$existingToken) {
                DB::table('api_tokens')->insert([
                    'id_alat_absen' => $alat->id_alat_absen,
                    'token' => Str::random(64),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    // Existing relationships
    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'id_alat_absen');
    }

    public function apiToken()
    {
        return $this->hasOne(ApiToken::class, 'id_alat_absen');
    }
}
