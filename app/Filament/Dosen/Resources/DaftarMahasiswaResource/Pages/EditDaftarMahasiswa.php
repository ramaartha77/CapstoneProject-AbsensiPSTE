<?php

namespace App\Filament\Dosen\Resources\DaftarMahasiswaResource\Pages;


use App\Filament\Dosen\Resources\DaftarMahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDaftarMahasiswa extends EditRecord
{
    protected static string $resource = DaftarMahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
