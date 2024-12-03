<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 't_ruangan';
    protected $primaryKey = 'id_ruangan';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = ['id_ruangan', 'nama_ruangan']; // Added id_ruangan to fillable
}
