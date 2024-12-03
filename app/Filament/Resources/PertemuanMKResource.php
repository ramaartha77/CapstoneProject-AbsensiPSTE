<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PertemuanMKResource\Pages;
use App\Models\Kelas;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;

class PertemuanMKResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $modelLabel = 'Kelas dengan Mahasiswa';
    protected static ?string $pluralModelLabel = 'Kelas dengan Mahasiswa';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('matkul.nama_matkul')
                    ->label('Mata Kuliah')
                    ->searchable(),
                TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable(),
                TextColumn::make('mahasiswa_count')
                    ->label('Jumlah Mahasiswa')
                    ->counts('mahasiswa'),
                TextColumn::make('account.nama')
                    ->label('Dosen Pengampu')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('semester')
                    ->label('Tahun Semester')
                    ->relationship('semester', 'nama_smt') // Adjust based on your relationship
                    ->options(function () {
                        return \App\Models\Smt::query()
                            ->orderBy('id_smt')
                            ->pluck('nama_smt', 'id_smt')
                            ->toArray();
                    }),
            ])
            ->actions([
                Action::make('manage_pertemuan')
                    ->label('Kelola Pertemuan')
                    ->icon('heroicon-o-pencil-square')
                    ->url(function (Kelas $record) {
                        return static::getUrl('manage-pertemuan', ['record' => $record->id_kelas]);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPertemuanMKS::route('/'),
            'manage-pertemuan' => Pages\ManagePertemuanPage::route('/{record}/manage-pertemuan'),
        ];
    }
}
