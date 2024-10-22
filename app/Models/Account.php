<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;

class Account extends Authenticatable implements FilamentUser, HasName
{
    use Notifiable;

    protected $table = 'accounts';
    protected $primaryKey = 'id_akun';

    protected $fillable = [
        'nama',
        'email',
        'username',
        'password',
        'role',
        'foto',
        'UID',
        'nim'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // Or implement your own logic here
    }

    public function getName(): string
    {
        Log::info('getName called', [
            'id' => $this->id_akun,
            'nama' => $this->nama,
            'username' => $this->username,
            'attributes' => $this->getAttributes()
        ]);
        return $this->nama ?? $this->username ?? 'Unknown';
    }

    public function getFilamentName(): string
    {
        return $this->getName();
    }

    public function getUserName(): string
    {
        return $this->username;
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    // Relasi ke KRS
    public function krs()
    {
        return $this->hasMany(Krs::class, 'id_akun');
    }
    // Relasi ke Kehadiran
    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'id_akun');
    }
}
