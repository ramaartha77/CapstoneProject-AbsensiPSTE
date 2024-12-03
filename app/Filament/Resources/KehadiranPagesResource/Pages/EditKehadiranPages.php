<?php

namespace App\Filament\Resources\KehadiranPagesResource\Pages;

use App\Filament\Resources\KehadiranPagesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKehadiranPages extends EditRecord
{
    protected static string $resource = KehadiranPagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
