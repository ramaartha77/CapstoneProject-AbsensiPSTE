<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;
use Filament\Forms\Component;
use App\Models\Kehadiran;
use Filament\Tables\Filters\SelectFilter;


class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $modelLabel = 'Kelas';
    protected static ?string $pluralModelLabel = 'Kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_matkul')
                    ->label('Mata Kuliah')
                    ->relationship('matkul', 'nama_matkul')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('id_akun')
                    ->label('Dosen')
                    ->relationship('account', 'nama', function ($query) {
                        $query->where('role', 'dosen');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->required()
                    ->maxLength(50),

                Forms\Components\Select::make('id_ruangan')
                    ->label('Ruangan')
                    ->options(function () {
                        return \App\Models\Ruangan::query()
                            ->orderBy('id_ruangan')
                            ->pluck('nama_ruangan', 'id_ruangan');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TimePicker::make('waktu_mulai')
                    ->label('Waktu Mulai')
                    ->required()
                    ->seconds(false)
                    ->displayFormat('H:i')
                    ->hoursStep(1)
                    ->minutesStep(1),

                Forms\Components\TimePicker::make('waktu_selesai')
                    ->label('Waktu Selesai')
                    ->required()
                    ->seconds(false)
                    ->displayFormat('H:i')
                    ->hoursStep(1)
                    ->minutesStep(1),

                Forms\Components\Select::make('hari')
                    ->label('Hari')
                    ->options([
                        'Senin' => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu' => 'Rabu',
                        'Kamis' => 'Kamis',
                        'Jumat' => 'Jumat',
                    ])
                    ->required(),

                Forms\Components\Select::make('id_smt')
                    ->label('Semester')
                    ->relationship('semester', 'nama_smt')
                    ->options(function () {
                        return \App\Models\Smt::query()
                            ->orderBy('id_smt')
                            ->pluck('nama_smt', 'id_smt');
                    })
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        // Find the semester and set thn_smt to its nama_smt
                        $semester = \App\Models\Smt::find($state);
                        if ($semester) {
                            $set('thn_smt', $semester->nama_smt);
                        }
                    })
                    ->live()
                    ->searchable()
                    ->preload()
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_smt')
                    ->label('Tahun/Semester')
                    ->sortable(),

                Tables\Columns\TextColumn::make('matkul.nama_matkul')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('account.nama')
                    ->label('Dosen')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ruangan.nama_ruangan')
                    ->label('Ruangan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('hari')
                    ->label('Hari')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->label('Waktu Mulai'),

                Tables\Columns\TextColumn::make('waktu_selesai')
                    ->label('Waktu Selesai'),


            ])
            ->filters([
                SelectFilter::make('id_smt')
                    ->label('Filter by Tahun Semester')
                    ->options(function () {
                        return \App\Models\Smt::query()
                            ->orderBy('id_smt')
                            ->pluck('nama_smt', 'id_smt')
                            ->toArray();
                    }),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->actions([
                Action::make('recap')
                    ->label('Rekap')
                    ->icon('heroicon-o-document-text')
                    ->action(function (Kelas $record) {
                        // Get all pertemuan ordered by date
                        $pertemuans = $record->pertemuan()
                            ->orderBy('tgl_pertemuan')
                            ->get();

                        // Get all students ordered by name
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

                                // Get status or set as null if no record exists
                                $studentAttendance[] = $kehadiran ? $kehadiran->status : null;
                            }

                            $attendanceData[] = [
                                'student' => $student,
                                'attendance' => $studentAttendance
                            ];
                        }

                        // Generate PDF in landscape mode
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
