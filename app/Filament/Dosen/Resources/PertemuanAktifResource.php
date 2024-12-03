<?php

namespace App\Filament\Dosen\Resources;

use App\Models\Pertemuan;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Dosen\Resources\DaftarKelasResource\Pages\DaftarMahasiswa;

class PertemuanAktifResource extends Resource
{
    protected static ?string $model = Pertemuan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Pertemuan Aktif';

    protected static ?string $modelLabel = 'Pertemuan Aktif';

    protected static ?string $pluralModelLabel = 'Pertemuan Aktif';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->required()
                    ->label('Kelas'),

                Forms\Components\TextInput::make('nama_pertemuan')
                    ->required()
                    ->label('Nama Pertemuan'),

                Forms\Components\DateTimePicker::make('tgl_pertemuan')
                    ->required()
                    ->label('Tanggal Pertemuan'),

                Forms\Components\TextInput::make('materi')
                    ->required()
                    ->label('Materi'),

                Forms\Components\Toggle::make('aktivasi_absen')
                    ->label('Aktifkan Absensi')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_pertemuan')
                    ->label('Nama Pertemuan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tgl_pertemuan')
                    ->label('Tanggal Pertemuan')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('materi')
                    ->label('Materi')
                    ->limit(50),

                Tables\Columns\ToggleColumn::make('aktivasi_absen')
                    ->label('Status Absensi')
            ])
            ->filters([
                Tables\Filters\Filter::make('aktivasi_absen')
                    ->label('Aktif Saat Ini')
                    ->query(fn(Builder $query) => $query->where('aktivasi_absen', true))
            ])
            ->actions([
                Tables\Actions\Action::make('goto_mahasiswa')
                    ->label('Daftar Mahasiswa')
                    ->icon('heroicon-o-users')
                    ->color('primary')
                    ->url(function ($record) {
                        return DaftarMahasiswa::getUrl(['record' => $record->id_pertemuan]);
                    }),

            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->where('aktivasi_absen', true));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Dosen\Resources\PertemuanAktifResource\Pages\ListPertemuanAktifs::route('/'),
            'create' => \App\Filament\Dosen\Resources\PertemuanAktifResource\Pages\CreatePertemuanAktif::route('/create'),
        ];
    }
}
