<?php

namespace App\Filament\Resources\RegristResource\Pages;

use App\Filament\Resources\RegristResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateRegrist extends CreateRecord
{
    protected static string $resource = RegristResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')->hidden();
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')->hidden();
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')->hidden();
    }
}
