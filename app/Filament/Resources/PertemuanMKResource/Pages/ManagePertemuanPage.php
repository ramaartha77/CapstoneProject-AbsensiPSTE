<?php

namespace App\Filament\Resources\PertemuanMKResource\Pages;

use App\Filament\Resources\PertemuanMKResource;
use App\Models\Kelas;
use App\Models\Kehadiran;
use App\Models\Pertemuan;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Forms\Set;


class ManagePertemuanPage extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public Kelas $record;

    protected static string $resource = PertemuanMKResource::class;
    protected static string $view = 'filament.resources.pertemuan-mk-resource.pages.manage-pertemuan';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn() => Pertemuan::where('id_kelas', $this->record->id_kelas))
            ->columns([
                TextColumn::make('nama_pertemuan')->label('Nama Pertemuan'),


            ])
            ->actions([
                EditAction::make('edit_attendance')
                    ->label('Edit Kehadiran')
                    ->modalWidth('7xl')
                    ->form(fn($record) => $this->createAttendanceForm($record))
                    ->action(fn($record, $data) => $this->updateAttendance($record, $data))
            ]);
    }

    protected function createAttendanceForm($record)
    {
        $bulkSection = Section::make('Bulk Update')
            ->schema([
                Select::make('bulk_status')
                    ->label('Pilih Status Bulk Update')
                    ->options([
                        'hadir' => 'Hadir',
                        'tidak hadir' => 'Tidak Hadir',
                        'izin' => 'Izin'
                    ])
                    ->placeholder('Pilih status')
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        if ($state) {
                            foreach ($this->record->mahasiswa as $mahasiswa) {
                                $set("status_{$mahasiswa->id_akun}", $state);
                            }
                        }
                    })
            ]);

        $mahasiswaFields = collect($this->record->mahasiswa)->map(function ($mahasiswa) use ($record) {
            $kehadiran = Kehadiran::where('id_pertemuan', $record->id_pertemuan)
                ->where('id_akun', $mahasiswa->id_akun)
                ->first();

            $currentStatus = $kehadiran ? $kehadiran->status : 'Belum Diset';

            return Select::make("status_{$mahasiswa->id_akun}")
                ->label("{$mahasiswa->nama} ({$mahasiswa->nim})")
                ->helperText("Status Saat Ini: {$currentStatus}")
                ->options([
                    'hadir' => 'Hadir',
                    'tidak hadir' => 'Tidak Hadir',
                    'izin' => 'Izin'
                ])
                ->default($kehadiran ? $kehadiran->status : null);
        })->toArray();

        return [
            $bulkSection,
            ...array_values($mahasiswaFields)
        ];
    }

    protected function updateAttendance($record, $data)
    {
        $updatedCount = 0;

        foreach ($this->record->mahasiswa as $mahasiswa) {
            $status = $data["status_{$mahasiswa->id_akun}"] ?? null;

            if ($status) {
                Kehadiran::updateOrCreate(
                    [
                        'id_pertemuan' => $record->id_pertemuan,
                        'id_akun' => $mahasiswa->id_akun
                    ],
                    [
                        'status' => $status,
                        'id_alat_absen' => null,
                        'waktu_absen' => now()
                    ]
                );
                $updatedCount++;
            }
        }

        if ($updatedCount > 0) {
            Notification::make()
                ->title('Kehadiran Diperbarui')
                ->body("Berhasil memperbarui kehadiran untuk $updatedCount mahasiswa")
                ->success()
                ->send();
        }
    }


    public static function getNavigationLabel(): string
    {
        return 'Kelola Pertemuan';
    }

    public function getBreadcrumbs(): array
    {
        return [
            route('filament.admin.resources.pertemuan-m-ks.index') => 'Pertemuan MK',
            '#' => 'Kelola Pertemuan',
        ];
    }
}
