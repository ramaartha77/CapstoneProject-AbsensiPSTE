<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smt extends Model
{
    use HasFactory;

    protected $table = 't_smt';
    protected $primaryKey = 'id_smt';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id_smt', 'nama_smt'];
}
