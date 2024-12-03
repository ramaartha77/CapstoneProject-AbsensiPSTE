<?php

namespace App\Filament\Mahasiswa\Resources\PilihkelasResource\Pages;

use App\Models\Kelas;
use App\Models\Krs;
use App\Filament\Mahasiswa\Resources\PilihkelasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditPilihkelas extends EditRecord
{
    protected static string $resource = PilihkelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->action(function (Kelas $record) {
                    Krs::where('id_akun', Auth::user()->id_akun)
                        ->where('id_kelas', $record->id_kelas)
                        ->delete();
                }),
        ];
    }
}
