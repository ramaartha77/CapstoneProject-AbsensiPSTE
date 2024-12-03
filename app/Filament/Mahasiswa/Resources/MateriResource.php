<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\MateriResource\Pages;
use App\Models\Kelas;
use App\Models\Pertemuan;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Grouping\Group;
use App\Models\Kehadiran;
use Filament\Tables\Filters\SelectFilter;
use App\Models\smt;

class MateriResource extends Resource
{
    protected static ?string $model = Pertemuan::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Materi Perkuliahan';

    protected static ?string $modelLabel = 'Materi';

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Pertemuan::whereHas('kelas', function (Builder $query) {
                    $query->whereHas('krs', function (Builder $krsQuery) {
                        $krsQuery->where('id_akun', Auth::user()->id_akun);
                    });
                })
            )
            ->columns([

                Tables\Columns\TextColumn::make('kehadiran'),

                Tables\Columns\TextColumn::make('kelas.matkul.nama_matkul')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_pertemuan')
                    ->label('Pertemuan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tgl_pertemuan')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('materi')
                    ->label('Materi')
                    ->wrap()
                    ->markdown()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kehadiran')
                    ->label('Status Kehadiran')
                    ->getStateUsing(function (Pertemuan $record) {
                        $kehadiran = Kehadiran::where('id_pertemuan', $record->id_pertemuan)
                            ->where('id_akun', Auth::user()->id_akun)
                            ->first();

                        if (!$kehadiran) {
                            return 'tidak hadir';
                        }

                        return $kehadiran->status;
                    })
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->color(fn(string $state): string => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'tidak hadir' => 'danger',
                    }),


            ])
            ->defaultSort('tgl_pertemuan', 'desc')
            ->groups([
                Group::make('kelas.matkul.nama_matkul')
                    ->label('Mata Kuliah')
                    ->collapsible(),
                Group::make('kelas.nama_kelas')
                    ->label('Kelas'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->options(function () {
                        return Kelas::whereHas('krs', function (Builder $query) {
                            $query->where('id_akun', Auth::user()->id_akun);
                        })
                            ->get()
                            ->mapWithKeys(function ($kelas) {
                                return [$kelas->id_kelas => "{$kelas->matkul->nama_matkul} - {$kelas->nama_kelas}"];
                            });
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn(Builder $query, $kelasId): Builder =>
                            $query->where('id_kelas', $kelasId)
                        );
                    }),
                SelectFilter::make('semester')
                    ->label('Tahun Semester')
                    ->relationship('kelas.semester', 'nama_smt')
                    ->options(function () {
                        return \App\Models\Smt::query()
                            ->orderBy('id_smt')
                            ->pluck('nama_smt', 'id_smt')
                            ->toArray();
                    }),
            ])

            ->defaultPaginationPageOption(10);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMateri::route('/'),
        ];
    }
}
