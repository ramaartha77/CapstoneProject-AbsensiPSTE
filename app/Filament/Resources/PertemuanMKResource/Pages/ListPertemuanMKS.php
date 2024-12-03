<?php

namespace App\Filament\Resources\PertemuanMKResource\Pages;

use App\Filament\Resources\PertemuanMKResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPertemuanMKS extends ListRecords
{
    protected static string $resource = PertemuanMKResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
