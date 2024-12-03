<?php

namespace App\Filament\Resources\MasterDataResource\Pages;

use App\Filament\Resources\MasterDataResource;
use App\Models\Ruangan;
use App\Models\Smt;
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
                ->modalHeading(fn() => 'Tambah ' . ($this->activeTab === 'semester' ? 'Semester' : 'Ruangan'))
                ->model(Ruangan::class)
                ->modelLabel(fn() => $this->activeTab === 'semester' ? 'Semester' : 'Ruangan')
                ->form(function () {
                    if ($this->activeTab === 'semester') {
                        return [
                            Forms\Components\TextInput::make('id_smt')
                                ->label('ID Semester')
                                ->required()
                                ->maxLength(255)
                                ->unique('t_smt', 'id_smt'),  // Add unique validation
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
                })
                ->using(function (array $data) {
                    if ($this->activeTab === 'semester') {
                        // For semester, use the provided ID
                        return Smt::create($data);
                    } else {
                        // For ruangan, get the next ID manually
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
                ->modifyQueryUsing(function (Builder $query) {
                    return Ruangan::query();
                })
                ->badge(Ruangan::count()),
            'semester' => Tab::make('Semester')
                ->modifyQueryUsing(function (Builder $query) {
                    return Smt::query();
                })
                ->badge(Smt::count()),
        ];
    }

    public function getDefaultActiveTab(): string
    {
        return 'ruangan';
    }
}
