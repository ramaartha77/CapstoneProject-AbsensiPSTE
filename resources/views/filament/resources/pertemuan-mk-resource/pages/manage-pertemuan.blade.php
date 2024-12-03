<x-filament-panels::page>
    <div class="space-y-4">
        <h2 class="text-xl font-semibold">
            Kelola Pertemuan untuk Kelas: {{ $this->record->nama_kelas }}
            ({{ $this->record->matkul->nama_matkul }})
        </h2>
        {{ $this->table }}
    </div>
</x-filament-panels::page>
