<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-green-100 p-4 rounded-lg">
            <div class="text-green-800 text-lg font-semibold">Hadir</div>
            <div class="text-2xl font-bold">{{ $summary['hadir'] }}</div>
        </div>

        <div class="bg-yellow-100 p-4 rounded-lg">
            <div class="text-yellow-800 text-lg font-semibold">Izin</div>
            <div class="text-2xl font-bold">{{ $summary['izin'] }}</div>
        </div>

        <div class="bg-red-100 p-4 rounded-lg ">
            <div class="text-red-800 text-lg font-semibold">Absen</div>
            <div class="text-2xl font-bold">{{$summary['total'] - ($summary['hadir'] + $summary['izin'])  }}</div>
        </div>
    </div>

    <div class="mt-4 p-4 bg-red-50 rounded-lg">
        <p class="text-red-600">
            Anda yakin ingin menutup sesi kelas ini? Mahasiswa tidak akan bisa melakukan absensi setelah sesi ditutup.
        </p>
    </div>
</div>
