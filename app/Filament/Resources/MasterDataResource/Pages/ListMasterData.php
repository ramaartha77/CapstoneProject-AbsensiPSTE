<?php

namespace App\Filament\Resources\MasterDataResource\Pages;

use App\Filament\Resources\MasterDataResource;
use App\Models\Ruangan;
use App\Models\Smt;
use App\Models\Alat;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ListMasterData extends ListRecords
{
    protected static string $resource = MasterDataResource::class;

    public ?string $activeTab = 'ruangan';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading(fn() => 'Tambah ' . match ($this->activeTab) {
                    'semester' => 'Semester',
                    'alat' => 'Alat',
                    default => 'Ruangan'
                })
                ->model(Ruangan::class)
                ->modelLabel(fn() => match ($this->activeTab) {
                    'semester' => 'Semester',
                    'alat' => 'Alat',
                    default => 'Ruangan'
                })
                ->form(function () {
                    if ($this->activeTab === 'semester') {
                        return [
                            Forms\Components\TextInput::make('id_smt')
                                ->label('ID Semester')
                                ->required()
                                ->maxLength(255)
                                ->unique('t_smt', 'id_smt'),
                            Forms\Components\TextInput::make('nama_smt')
                                ->label('Nama Semester')
                                ->required()
                                ->maxLength(255),
                        ];
                    }

                    if ($this->activeTab === 'alat') {
                        return [
                            Forms\Components\TextInput::make('id_alat_absen')
                                ->label('ID Alat')
                                ->required()
                                ->maxLength(20)
                                ->unique('alat_absen', 'id_alat_absen'),
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
                ->using(function (array $data) {
                    if ($this->activeTab === 'semester') {
                        return Smt::create($data);
                    } elseif ($this->activeTab === 'alat') {
                        return Alat::create([
                            'id_alat_absen' => $data['id_alat_absen'],
                            'nama_alat' => $data['nama_alat'],
                            'ruangan' => $data['ruangan']
                        ]);
                    } else {
                        $nextId = DB::table('t_ruangan')->max('id_ruangan') + 1;
                        return Ruangan::create([
                            'id_ruangan' => $nextId,
                            'nama_ruangan' => $data['nama_ruangan']
                        ]);
                    }
                })
        ];
    }

    public function getTabs(): array
    {
        return [
            'ruangan' => Tab::make('Ruangan')
                ->modifyQueryUsing(fn(Builder $query) => Ruangan::query())
                ->badge(Ruangan::count()),
            'semester' => Tab::make('Semester')
                ->modifyQueryUsing(fn(Builder $query) => Smt::query())
                ->badge(Smt::count()),
            'alat' => Tab::make('Alat')
                ->modifyQueryUsing(fn(Builder $query) => Alat::query())
                ->badge(Alat::count()),
        ];
    }

    public function getDefaultActiveTab(): string
    {
        return 'ruangan';
    }
}
