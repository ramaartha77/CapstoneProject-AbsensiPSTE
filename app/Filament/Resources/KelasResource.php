<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ->relationship('account', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('ruangan')
                    ->label('Ruangan')
                    ->required()
                    ->maxLength(45),

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

                Forms\Components\TimePicker::make('waktu')
                    ->label('Waktu')
                    ->required()
                    ->seconds(false)
                    ->displayFormat('H:i')
                    ->hoursStep(1)
                    ->minutesStep(30),

                Forms\Components\TextInput::make('thn_smt')
                    ->label('Tahun/Semester')
                    ->required()
                    ->maxLength(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('matkul.nama_matkul')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('account.nama')
                    ->label('Dosen')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('ruangan')
                    ->label('Ruangan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('hari')
                    ->label('Hari')
                    ->searchable(),

                Tables\Columns\TextColumn::make('waktu')
                    ->label('Waktu'),

                Tables\Columns\TextColumn::make('thn_smt')
                    ->label('Tahun/Semester'),
            ])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
