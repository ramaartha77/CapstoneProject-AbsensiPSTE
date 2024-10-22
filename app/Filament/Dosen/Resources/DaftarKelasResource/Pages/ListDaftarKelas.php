<?php

namespace App\Filament\Dosen\Resources\DaftarKelasResource\Pages;

use App\Filament\Dosen\Resources\DaftarKelasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDaftarKelas extends ListRecords
{
    protected static string $resource = DaftarKelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
