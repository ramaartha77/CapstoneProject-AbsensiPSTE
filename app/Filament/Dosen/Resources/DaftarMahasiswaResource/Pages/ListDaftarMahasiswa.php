<?php

namespace App\Filament\Mahasiswa\Resources\DaftarMahasiswaResource\Pages;


use App\Models\Kelas;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ListDaftarMahasiswa extends Page
{
    protected $record;

    public ?array $data = [];

    public function mount(Kelas $record)
    {
        $this->record = $record;
        $this->form->fill([
            'mahasiswa' => $record->mahasiswa->map(fn($mahasiswa) => [
                'id' => $mahasiswa->id,
                'nama' => $mahasiswa->nama,
                'nim' => $mahasiswa->nim,
                'status' => $mahasiswa->status
            ])->toArray()
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Repeater::make('mahasiswa')
                            ->schema([
                                TextInput::make('nama')
                                    ->label('Nama Mahasiswa')
                                    ->disabled(),
                                TextInput::make('nim')
                                    ->label('NIM')
                                    ->disabled(),
                                Select::make('status')
                                    ->label('Status Kehadiran')
                                    ->options([
                                        'hadir' => 'Hadir',
                                        'izin' => 'Izin',
                                        'tidak_hadir' => 'Tidak Hadir',
                                    ])
                                    ->native(false)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $record) {
                                        $this->save();
                                    }),
                            ])
                            ->columns(3)
                            ->disabled(fn() => !$this->record->pertemuan?->aktivasi_absen)
                            ->columnSpanFull()
                    ])
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data['mahasiswa'] as $item) {
            $this->record->mahasiswa()
                ->where('id', $item['id'])
                ->update([
                    'status' => $item['status'],
                    'waktu_absen' => now()
                ]);
        }

        $this->dispatch('notify', [
            'status' => 'success',
            'message' => 'Status kehadiran berhasil diperbarui'
        ]);
    }
}
