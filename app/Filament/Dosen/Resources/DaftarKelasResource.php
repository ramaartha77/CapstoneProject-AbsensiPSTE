<?php

namespace App\Filament\Dosen\Resources;

use App\Filament\Dosen\Resources\DaftarKelasResource\Pages;
use App\Models\Kelas;
use App\Models\Kehadiran;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;

class DaftarKelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = 'Mulai Kelas';
    protected static ?string $navigationLabel = 'Mulai Kelas';
    protected static ?string $pluralModelLabel = 'Mulai Kelas';
    protected static ?string $slug = 'daftar-kelas';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('hari')
                    ->label('Hari'),
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('matkul.nama_matkul')
                    ->label('Mata Kuliah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ruangan.nama_ruangan')
                    ->label('Ruangan'),
                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->label('Waktu'),
            ])
            ->actions([
                Action::make('recap')
                    ->label('Rekap')
                    ->icon('heroicon-o-document-text')
                    ->action(function (Kelas $record) {
                        $pertemuans = $record->pertemuan()
                            ->orderBy('tgl_pertemuan')
                            ->get();

                        $students = $record->mahasiswa()
                            ->orderBy('nama')
                            ->get();

                        $attendanceData = [];
                        foreach ($students as $student) {
                            $studentAttendance = [];
                            foreach ($pertemuans as $pertemuan) {
                                $kehadiran = Kehadiran::where('id_akun', $student->id_akun)
                                    ->where('id_pertemuan', $pertemuan->id_pertemuan)
                                    ->first();

                                $studentAttendance[] = $kehadiran ? $kehadiran->status : null;
                            }

                            $attendanceData[] = [
                                'student' => $student,
                                'attendance' => $studentAttendance
                            ];
                        }

                        $pdf = PDF::loadView('pdf.attendance-recap', [
                            'kelas' => $record,
                            'pertemuans' => $pertemuans,
                            'attendanceData' => $attendanceData
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, "rekap-kehadiran-{$record->nama_kelas}.pdf");
                    })
                    ->button()
            ])
            ->query(fn() => Kelas::where('id_akun', auth()->user()->id_akun))
            ->filters([
                Tables\Filters\SelectFilter::make('hari')
                    ->label('Hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                        'Sabtu' => 'Sabtu',
                        'Minggu' => 'Minggu',
                    ]),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDaftarKelas::route('/'),
            'daftarMahasiswa' => Pages\DaftarMahasiswa::route('/{record}/daftar-mahasiswa'),
        ];
    }
}
