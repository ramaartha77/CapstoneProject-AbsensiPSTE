<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasterDataResource\Pages;
use App\Models\Ruangan;
use App\Models\Smt;
use Filament\Forms;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Models\Alat;
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
                return match ($livewire->activeTab) {
                    'semester' => Smt::query(),
                    'alat' => Alat::query(),
                    default => Ruangan::query(),
                };
            })
            ->persistSortInSession()
            ->persistFiltersInSession()
            ->columns([
                // Ruangan Columns
                TextColumn::make('id_ruangan')
                    ->label('ID')
                    ->sortable()
                    ->visible(fn($livewire) => $livewire->activeTab === 'ruangan'),
                TextColumn::make('nama_ruangan')
                    ->label('Nama Ruangan')
                    ->searchable()
                    ->sortable()
                    ->visible(fn($livewire) => $livewire->activeTab === 'ruangan'),

                // Semester Columns
                TextColumn::make('id_smt')
                    ->label('ID')
                    ->sortable()
                    ->visible(fn($livewire) => $livewire->activeTab === 'semester'),
                TextColumn::make('nama_smt')
                    ->label('Nama Semester')
                    ->searchable()
                    ->sortable()
                    ->visible(fn($livewire) => $livewire->activeTab === 'semester'),

                // Alat Columns
                TextColumn::make('id_alat_absen')
                    ->label('ID')
                    ->sortable()
                    ->visible(fn($livewire) => $livewire->activeTab === 'alat'),
                TextColumn::make('nama_alat')
                    ->label('Nama Alat')
                    ->searchable()
                    ->sortable()
                    ->visible(fn($livewire) => $livewire->activeTab === 'alat'),
                TextColumn::make('ruangan')
                    ->label('Lokasi Alat')
                    ->searchable()
                    ->sortable()
                    ->visible(fn($livewire) => $livewire->activeTab === 'alat'),
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

                        if ($livewire->activeTab === 'alat') {
                            return [
                                Forms\Components\TextInput::make('id_alat_absen')
                                    ->label('ID Alat')
                                    ->required()
                                    ->maxLength(20)
                                    ->unique('alat_absen', 'id_alat_absen', ignoreRecord: true),
                                Forms\Components\TextInput::make('nama_alat')
                                    ->label('Nama Alat')
                                    ->required()
                                    ->maxLength(45),
                                Forms\Components\TextInput::make('ruangan')
                                    ->label('Lokasi Alat')
                                    ->required()
                                    ->maxLength(45),
                            ];
                        }

                        return [
                            Forms\Components\TextInput::make('nama_ruangan')
                                ->label('Nama Ruangan')
                                ->required()
                                ->maxLength(255),
                        ];
                    })
                    ->using(function ($data, $record) {
                        if ($record instanceof Smt) {
                            $record->update($data);
                        } elseif ($record instanceof Alat) {
                            $record->update([
                                'id_alat_absen' => $data['id_alat_absen'],
                                'nama_alat' => $data['nama_alat'],
                                'ruangan' => $data['ruangan']
                            ]);
                        } elseif ($record instanceof Ruangan) {
                            $record->update($data);
                        }
                        return $record;
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
