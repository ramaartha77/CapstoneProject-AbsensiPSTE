<?php

namespace App\Filament\Resources\RegristResource\Pages;

use App\Filament\Resources\RegristResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRegrist extends CreateRecord
{
    protected static string $resource = RegristResource::class;


    protected function getFormActions(): array
    {
        return [];
    }


    protected function getHeaderActions(): array
    {
        return [];
    }


    public function create(bool $another = false): void
    {
        return;
    }
}
