<?php

namespace App\Filament\Mahasiswa\Resources\PilihkelasResource\Pages;

use App\Filament\Mahasiswa\Resources\PilihkelasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPilihkelas extends ListRecords
{
    protected static string $resource = PilihkelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
