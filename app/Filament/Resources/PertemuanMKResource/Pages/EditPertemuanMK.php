<?php

namespace App\Filament\Resources\PertemuanMKResource\Pages;

use App\Filament\Resources\PertemuanMKResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPertemuanMK extends EditRecord
{
    protected static string $resource = PertemuanMKResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
