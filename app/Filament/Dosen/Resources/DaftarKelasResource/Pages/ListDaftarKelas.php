<?php

namespace App\Filament\Dosen\Resources\DaftarKelasResource\Pages;

use App\Filament\Dosen\Resources\DaftarKelasResource;
use App\Models\Kehadiran;
use App\Models\Pertemuan;
use App\Models\Account;
use App\Models\Kelas;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\DatePicker;


class ListDaftarKelas extends ListRecords
{
    protected static string $resource = DaftarKelasResource::class;

    public function getTitle(): string | Htmlable
    {
        return 'Daftar Kelas';
    }

    protected function getHeaderActions(): array
    {
        $idDosen = auth()->user()->id_akun;

        return [
            CreateAction::make('mulai_kelas')
                ->label('Mulai Kelas')
                ->modalActions(fn($action) => [
                    $action->makeModalSubmitAction('create', ['exit' => true])
                        ->color('primary')
                ])
                ->form([
                    Select::make('nama_kelas')
                        ->options(Kelas::where('id_akun', $idDosen)
                            ->pluck('nama_kelas', 'id_kelas'))
                        ->required()
                        ->live()
                        ->label('Pilih Kelas'),

                    Select::make('pertemuan')
                        ->options(function (callable $get) {
                            $kelasId = $get('nama_kelas');

                            if (!$kelasId) {
                                return [];
                            }

                            $existingPertemuan = Pertemuan::where('id_kelas', $kelasId)
                                ->pluck('nama_pertemuan')
                                ->toArray();

                            $allPertemuan = collect(range(1, 16))->map(function ($number) {
                                return "Pertemuan $number";
                            });


                            return $allPertemuan->reject(function ($pertemuan) use ($existingPertemuan) {
                                return in_array($pertemuan, $existingPertemuan);
                            })->mapWithKeys(function ($pertemuan) {
                                return [$pertemuan => $pertemuan];
                            });
                        })
                        ->required()
                        ->label('Nama Pertemuan'),

                    DatePicker::make('tgl_pertemuan')
                        ->required()
                        ->label('Tanggal Pertemuan'),

                    Select::make('type_pertemuan')
                        ->options([
                            'online' => 'Online',
                            'offline' => 'Offline',
                        ])
                        ->required()
                        ->label('Tipe Pertemuan'),

                    Textarea::make('materi')
                        ->label('Materi')
                        ->required(),

                ])
                ->action(function (array $data) {

                    $pertemuan = Pertemuan::create([
                        'id_kelas' => $data['nama_kelas'],
                        'nama_pertemuan' => $data['pertemuan'],
                        'materi' => $data['materi'],
                        'tgl_pertemuan' => $data['tgl_pertemuan'],
                        'aktivasi_absen' => true,
                        'type_pertemuan' => $data['type_pertemuan'],
                    ]);

                    Notification::make()
                        ->title('Kelas berhasil dimulai')
                        ->success()
                        ->send();

                    return redirect(DaftarKelasResource::getUrl('daftarMahasiswa', ['record' => $pertemuan->id_pertemuan]));
                }),
        ];
    }
}
