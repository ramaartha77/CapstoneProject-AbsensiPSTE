<?php

namespace App\Filament\Dosen\Resources\DaftarKelasResource\Pages;

use App\Filament\Dosen\Resources\DaftarKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDaftarKelas extends EditRecord
{
    protected static string $resource = DaftarKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
