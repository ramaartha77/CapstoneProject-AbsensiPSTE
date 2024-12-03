<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\DaftarMahasiswaResource\Pages;
use App\Models\Kelas;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class DaftarMahasiswaResource extends Resource
{
    protected static ?string $model = Kelas::class;
    protected static ?string $navigationLabel = 'Daftar Mahasiswa';
    protected static ?string $navigationGroup = 'Mahasiswa';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDaftarMahasiswa::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'dosen';
    }
}
