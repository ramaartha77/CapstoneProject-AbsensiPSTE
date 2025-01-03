<?php

namespace App\Filament\Mahasiswa\Resources;

use App\Filament\Mahasiswa\Resources\PilihkelasResource\Pages;
use App\Models\Kelas;
use App\Models\Krs;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use App\Models\smt;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\Auth;

class PilihkelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Pilih Kelas';

    protected static ?string $modelLabel = 'Pilihan Kelas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Pilih Kelas')
                    ->description('Silakan pilih kelas yang akan diambil')
                    ->schema([
                        Forms\Components\Select::make('kelas_1')
                            ->label('Kelas 1')
                            ->options(function () {
                                return Kelas::query()
                                    ->with(['matkul', 'account'])
                                    ->get()
                                    ->mapWithKeys(function ($kelas) {
                                        return [
                                            $kelas->id_kelas => "{$kelas->matkul->nama_matkul} - {$kelas->nama_kelas} - {$kelas->hari} {$kelas->waktu} - {$kelas->account->nama}"
                                        ];
                                    });
                            })
                            ->required()
                            ->distinct()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (string $state, Forms\Components\Select $component) {
                                // Get parent component (the form)
                                $parent = $component->getContainer();

                                // Check other fields for duplicate values
                                $fields = ['kelas_2', 'kelas_3', 'kelas_4', 'kelas_5', 'kelas_6', 'kelas_7'];
                                foreach ($fields as $field) {
                                    // Get the component for this field
                                    if ($fieldComponent = $parent->getComponent($field)) {
                                        // If this field has the same value, clear it
                                        if ($fieldComponent->getState() === $state) {
                                            $fieldComponent->setState(null);
                                        }
                                    }
                                }
                            }),

                        Forms\Components\Select::make('kelas_2')
                            ->label('Kelas 2')
                            ->options(function () {
                                return Kelas::query()
                                    ->with(['matkul', 'account'])
                                    ->get()
                                    ->mapWithKeys(function ($kelas) {
                                        return [
                                            $kelas->id_kelas => "{$kelas->matkul->nama_matkul} - {$kelas->nama_kelas} - {$kelas->hari} {$kelas->waktu} - {$kelas->account->nama}"
                                        ];
                                    });
                            })
                            ->distinct()
                            ->searchable()
                            ->live(),

                        Forms\Components\Select::make('kelas_3')
                            ->label('Kelas 3')
                            ->options(function () {
                                return Kelas::query()
                                    ->with(['matkul', 'account'])
                                    ->get()
                                    ->mapWithKeys(function ($kelas) {
                                        return [
                                            $kelas->id_kelas => "{$kelas->matkul->nama_matkul} - {$kelas->nama_kelas} - {$kelas->hari} {$kelas->waktu} - {$kelas->account->nama}"
                                        ];
                                    });
                            })
                            ->distinct()
                            ->searchable()
                            ->live(),

                        Forms\Components\Select::make('kelas_4')
                            ->label('Kelas 4')
                            ->options(function () {
                                return Kelas::query()
                                    ->with(['matkul', 'account'])
                                    ->get()
                                    ->mapWithKeys(function ($kelas) {
                                        return [
                                            $kelas->id_kelas => "{$kelas->matkul->nama_matkul} - {$kelas->nama_kelas} - {$kelas->hari} {$kelas->waktu} - {$kelas->account->nama}"
                                        ];
                                    });
                            })
                            ->distinct()
                            ->searchable()
                            ->live(),

                        Forms\Components\Select::make('kelas_5')
                            ->label('Kelas 5')
                            ->options(function () {
                                return Kelas::query()
                                    ->with(['matkul', 'account'])
                                    ->get()
                                    ->mapWithKeys(function ($kelas) {
                                        return [
                                            $kelas->id_kelas => "{$kelas->matkul->nama_matkul} - {$kelas->nama_kelas} - {$kelas->hari} {$kelas->waktu} - {$kelas->account->nama}"
                                        ];
                                    });
                            })
                            ->distinct()
                            ->searchable()
                            ->live(),

                        Forms\Components\Select::make('kelas_6')
                            ->label('Kelas 6')
                            ->options(function () {
                                return Kelas::query()
                                    ->with(['matkul', 'account'])
                                    ->get()
                                    ->mapWithKeys(function ($kelas) {
                                        return [
                                            $kelas->id_kelas => "{$kelas->matkul->nama_matkul} - {$kelas->nama_kelas} - {$kelas->hari} {$kelas->waktu} - {$kelas->account->nama}"
                                        ];
                                    });
                            })
                            ->distinct()
                            ->searchable()
                            ->live(),

                        Forms\Components\Select::make('kelas_7')
                            ->label('Kelas 7')
                            ->options(function () {
                                return Kelas::query()
                                    ->with(['matkul', 'account'])
                                    ->get()
                                    ->mapWithKeys(function ($kelas) {
                                        return [
                                            $kelas->id_kelas => "{$kelas->matkul->nama_matkul} - {$kelas->nama_kelas} - {$kelas->hari} {$kelas->waktu} - {$kelas->account->nama}"
                                        ];
                                    });
                            })
                            ->distinct()
                            ->searchable()
                            ->live(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Kelas::whereHas('krs', function (Builder $query) {
                    $query->where('id_akun', Auth::user()->id_akun);
                })
            )
            ->columns([
                Tables\Columns\TextColumn::make('matkul.nama_matkul')
                    ->label('Mata Kuliah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('matkul.sks')
                    ->label('SKS'),
                Tables\Columns\TextColumn::make('account.nama')
                    ->label('Dosen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hari')
                    ->label('Hari'),
                Tables\Columns\TextColumn::make('waktu')
                    ->label('Waktu'),
                Tables\Columns\TextColumn::make('ruangan.nama_ruangan')
                    ->label('Ruangan'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->action(function (Kelas $record) {
                        Krs::where('id_akun', Auth::user()->id_akun)
                            ->where('id_kelas', $record->id_kelas)
                            ->delete();
                    }),

            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPilihkelas::route('/'),
            'create' => Pages\CreatePilihkelas::route('/create'),
            'edit' => Pages\EditPilihkelas::route('/{record}/edit'),
        ];
    }
}
