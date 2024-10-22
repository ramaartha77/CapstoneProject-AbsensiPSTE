<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatkulResource\Pages;
use App\Models\Matkul;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class MatkulResource extends Resource
{
    protected static ?string $model = Matkul::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id_matkul')
                    ->label('ID Matkul')
                    ->required()
                    ->maxLength(45),

                Select::make('id_akun')
                    ->label('Dosen')
                    ->required()
                    ->relationship('account', 'nama'),

                TextInput::make('nama_matkul')
                    ->label('Nama Matkul')
                    ->required()
                    ->maxLength(45),

                TextInput::make('sks')
                    ->label('SKS')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(6),

                TextInput::make('semester')
                    ->label('Semester')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(8),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_matkul')->label('ID Matkul'),
                Tables\Columns\TextColumn::make('account.nama')->label('Dosen'),
                Tables\Columns\TextColumn::make('nama_matkul')->label('Nama Matkul'),
                Tables\Columns\TextColumn::make('sks')->label('SKS'),
                Tables\Columns\TextColumn::make('semester')->label('Semester'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMatkuls::route('/'),
            'create' => Pages\CreateMatkul::route('/create'),
            'edit' => Pages\EditMatkul::route('/{record}/edit'),
        ];
    }
}
