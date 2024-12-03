<?php

namespace App\Filament\Dosen\Resources\PertemuanAktifResource\Pages;

use App\Filament\Dosen\Resources\PertemuanAktifResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPertemuanAktifs extends ListRecords
{
    protected static string $resource = PertemuanAktifResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
