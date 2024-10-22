<?php

namespace App\Filament\Resources\MatkulResource\Pages;

use App\Filament\Resources\MatkulResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatkul extends EditRecord
{
    protected static string $resource = MatkulResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
