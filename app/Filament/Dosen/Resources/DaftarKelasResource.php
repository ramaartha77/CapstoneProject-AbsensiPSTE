<?php

namespace App\Filament\Dosen\Resources;

use App\Filament\Dosen\Resources\DaftarKelasResource\Pages;
use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\Account;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

class DaftarKelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Daftar Kelas';

    protected static ?string $navigationLabel = 'Daftar Kelas';

    protected static ?string $pluralModelLabel = 'Daftar Kelas';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('id_matkul')
                ->relationship('matkul', 'nama_matkul')
                ->required()
                ->searchable()
                ->preload()
                ->label('Mata Kuliah'),

            Select::make('id_akun')
                ->relationship('account', 'nama')
                ->required()
                ->searchable()
                ->preload()
                ->label('Dosen'),

            TextInput::make('nama_kelas')
                ->required()
                ->maxLength(255)
                ->label('Nama Kelas'),

            TextInput::make('ruangan')
                ->required()
                ->maxLength(255)
                ->label('Ruangan'),

            Select::make('hari')
                ->options([
                    'Senin' => 'Senin',
                    'Selasa' => 'Selasa',
                    'Rabu' => 'Rabu',
                    'Kamis' => 'Kamis',
                    'Jumat' => 'Jumat',
                    'Sabtu' => 'Sabtu',
                ])
                ->required()
                ->label('Hari'),

            TextInput::make('waktu')
                ->required()
                ->label('Waktu'),

            TextInput::make('thn_smt')
                ->required()
                ->label('Tahun/Semester')
                ->placeholder('Contoh: 2023/2024-1'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kelas')
                    ->sortable()
                    ->searchable()
                    ->label('Nama Kelas'),

                TextColumn::make('matkul.nama_matkul')
                    ->sortable()
                    ->searchable()
                    ->label('Mata Kuliah'),

                TextColumn::make('account.nama')
                    ->sortable()
                    ->searchable()
                    ->label('Dosen'),

                TextColumn::make('ruangan')
                    ->sortable()
                    ->searchable()
                    ->label('Ruangan'),

                TextColumn::make('hari')
                    ->sortable()
                    ->searchable()
                    ->label('Hari'),

                TextColumn::make('waktu')
                    ->sortable()
                    ->searchable()
                    ->label('Waktu'),

                TextColumn::make('thn_smt')
                    ->sortable()
                    ->searchable()
                    ->label('Tahun/Semester'),
            ])
            ->defaultSort('nama_kelas', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListDaftarKelas::route('/'),
            'create' => Pages\CreateDaftarKelas::route('/create'),
            'edit' => Pages\EditDaftarKelas::route('/{record}/edit'),
        ];
    }
}
