<?php

namespace App\Filament\Resources\KehadiranPagesResource\Pages;

use App\Filament\Resources\KehadiranPagesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKehadiranPages extends ListRecords
{
    protected static string $resource = KehadiranPagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
