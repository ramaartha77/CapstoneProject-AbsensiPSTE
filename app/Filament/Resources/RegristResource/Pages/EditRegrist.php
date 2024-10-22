<?php

namespace App\Filament\Resources\RegristResource\Pages;

use App\Filament\Resources\RegristResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegrist extends EditRecord
{
    protected static string $resource = RegristResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
