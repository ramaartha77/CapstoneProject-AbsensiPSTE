<?php

namespace App\Filament\Dosen\Resources\DaftarKelasResource\Pages;

use App\Filament\Dosen\Resources\DaftarKelasResource;
use App\Models\Account;
use App\Models\Kehadiran;
use App\Models\Pertemuan;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;

class DaftarMahasiswa extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = DaftarKelasResource::class;

    protected static string $view = 'filament.pages.daftar-mahasiswa';

    public $pertemuan_id;
    public $kelas_id;

    public function mount($record)
    {
        $this->pertemuan_id = $record;

        $pertemuan = Pertemuan::find($record);
        if ($pertemuan) {
            $this->kelas_id = $pertemuan->id_kelas;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh_page')
                ->label('Refresh')
                ->color('gray')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    $this->redirect(request()->header('Referer'));
                }),

            Action::make('close_session')
                ->label('Tutup Sesi')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->modalHeading('Ringkasan Kehadiran & Tutup Sesi')
                ->modalDescription(fn() => new \Illuminate\Support\HtmlString(
                    view('filament.pages.attendance-summary', [
                        'summary' => $this->getAttendanceSummary()
                    ])->render()
                ))
                ->modalSubmitActionLabel('Tutup Sesi')
                ->modalCancelActionLabel('Kembali')
                ->size(ActionSize::Large)
                ->action(function () {
                    // Ambil pertemuan berdasarkan ID
                    $pertemuan = Pertemuan::find($this->pertemuan_id);

                    if ($pertemuan) {
                        // Update status pertemuan menjadi tidak aktif
                        $pertemuan->update([
                            'aktivasi_absen' => false,
                            'waktu_selesai' => now()
                        ]);

                        // Mengambil semua mahasiswa di kelas tersebut
                        $mahasiswaList = Account::whereHas('krs', function (Builder $query) {
                            $query->where('id_kelas', $this->kelas_id);
                        })->where('role', 'mahasiswa')->get();

                        // Loop untuk setiap mahasiswa dan cek kehadirannya
                        foreach ($mahasiswaList as $mahasiswa) {
                            $kehadiran = Kehadiran::where('id_akun', $mahasiswa->id_akun)
                                ->where('id_pertemuan', $this->pertemuan_id)
                                ->first();

                            // Jika tidak ada data kehadiran, tandai sebagai "tidak hadir"
                            if (!$kehadiran) {
                                Kehadiran::create([
                                    'id_akun' => $mahasiswa->id_akun,
                                    'id_pertemuan' => $this->pertemuan_id,
                                    'status' => 'tidak hadir',
                                    'waktu_absen' => now(),
                                    'id_alat_absen' => null,
                                ]);
                            }
                        }

                        Notification::make()
                            ->title('Sesi kelas berhasil ditutup')
                            ->success()
                            ->send();

                        return redirect()->route('filament.dosen.resources.daftar-kelas.index');
                    }
                }),
        ];
    }


    private function getAttendanceSummary(): array
    {
        $totalStudents = Account::query()
            ->whereHas('krs', function (Builder $query) {
                $query->where('id_kelas', $this->kelas_id);
            })
            ->where('role', 'mahasiswa')
            ->count();

        $attendance = Kehadiran::where('id_pertemuan', $this->pertemuan_id)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $hadir = $attendance['hadir'] ?? 0;
        $izin = $attendance['izin'] ?? 0;
        $tidak_hadir = $totalStudents - ($hadir + $izin);

        return [
            'total' => $totalStudents,
            'hadir' => $hadir,
            'izin' => $izin,
            'tidak_hadir' => max(0, $tidak_hadir),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Account::query()
                    ->whereHas('krs', function (Builder $query) {
                        $query->where('id_kelas', $this->kelas_id);
                    })
                    ->where('role', 'mahasiswa')
            )
            ->columns([
                ImageColumn::make('foto')
                    ->label('Photo')
                    ->getStateUsing(fn($record) => $record->foto
                        ? url('storage/' . $record->foto)
                        : url('images/default-avatar.png'))
                    ->height(50)
                    ->width(50),

                TextColumn::make('nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),

                SelectColumn::make('kehadiran_status')
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'tidak hadir' => 'Tidak Hadir',
                        'izin' => 'Izin',
                    ])
                    ->selectablePlaceholder(false)
                    ->state(function (Account $record): string {
                        $kehadiran = Kehadiran::where('id_akun', $record->id_akun)
                            ->where('id_pertemuan', $this->pertemuan_id)
                            ->first();

                        return $kehadiran?->status ?? 'tidak hadir';
                    })
                    ->updateStateUsing(function (Account $record, string $state) {
                        Kehadiran::updateOrCreate(
                            [
                                'id_akun' => $record->id_akun,
                                'id_pertemuan' => $this->pertemuan_id,
                            ],
                            [
                                'status' => $state,
                                'waktu_absen' => now(),
                                'id_alat_absen' => null,
                            ]
                        );
                    }),

                TextColumn::make('kehadiran.waktu_absen')
                    ->label('Waktu Absen')
                    ->dateTime()
                    ->sortable()
                    ->getStateUsing(function (Account $record): ?string {
                        $kehadiran = Kehadiran::where('id_akun', $record->id_akun)
                            ->where('id_pertemuan', $this->pertemuan_id)
                            ->first();

                        return $kehadiran?->waktu_absen;
                    }),
            ])
            ->defaultSort('nama', 'asc')
            ->poll('10s');
    }
}
