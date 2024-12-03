<?php

namespace App\Filament\Mahasiswa\Resources\PilihkelasResource\Pages;

use App\Filament\Mahasiswa\Resources\PilihkelasResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Krs;
use App\Models\Kelas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreatePilihkelas extends CreateRecord
{
    protected static string $resource = PilihkelasResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Get the current authenticated user's ID
        $userId = Auth::user()->id_akun;

        // Create KRS entries for each selected class
        for ($i = 1; $i <= 7; $i++) {
            $kelasId = $data["kelas_$i"] ?? null;

            if ($kelasId) {
                try {
                    Krs::create([
                        'id_akun' => $userId,
                        'id_kelas' => $kelasId,
                    ]);
                } catch (\Exception $e) {
                    // Handle any duplicate entries or other errors
                    continue;
                }
            }
        }

        // Return the first Kelas as we need to return something
        return Kelas::find($data['kelas_1']);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
