<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterDataResource\Pages;
use App\Models\Ruangan;
use App\Models\Smt;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class MasterDataResource extends Resource
{
    protected static ?string $model = Ruangan::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Master Data';
    protected static ?string $pluralModelLabel = 'Master Data';

    public static function table(Table $table): Table
    {
        return $table
            ->query(function ($livewire) {
                return $livewire->activeTab === 'semester'
                    ? Smt::query()
                    : Ruangan::query();
            })
            ->persistSortInSession()
            ->persistFiltersInSession()
            ->columns([
                TextColumn::make('id_ruangan')
                    ->label('ID')
                    ->sortable()
                    ->visible(fn($livewire) => $livewire->activeTab === 'ruangan'),
                TextColumn::make('nama_ruangan')
                    ->label('Nama Ruangan')
                    ->searchable()
                    ->sortable()
                    ->visible(fn($livewire) => $livewire->activeTab === 'ruangan'),

                TextColumn::make('id_smt')
                    ->label('ID')
                    ->sortable()
                    ->visible(fn($livewire) => $livewire->activeTab === 'semester'),
                TextColumn::make('nama_smt')
                    ->label('Nama Semester')
                    ->searchable()
                    ->sortable()
                    ->visible(fn($livewire) => $livewire->activeTab === 'semester'),
            ])
            ->actions([
                EditAction::make()
                    ->form(function ($record, $livewire) {
                        if ($livewire->activeTab === 'semester') {
                            return [
                                Forms\Components\TextInput::make('nama_smt')
                                    ->label('Nama Semester')
                                    ->required()
                                    ->maxLength(255),
                            ];
                        }

                        return [
                            Forms\Components\TextInput::make('nama_ruangan')
                                ->label('Nama Ruangan')
                                ->required()
                                ->maxLength(255),
                        ];
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasterData::route('/'),
        ];
    }
}
