<?php

namespace App\Filament\Resources\RegristResource\Pages;

use App\Filament\Resources\RegristResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRegrist extends ViewRecord
{
    protected static string $resource = RegristResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
