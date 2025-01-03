<!DOCTYPE html>
<html>

<head>
    <title>Attendance Recap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            padding: 0;
            font-size: 14px;
        }

        .class-info {
            margin-bottom: 10px;
            font-size: 11px;
        }

        .class-info p {
            margin: 2px 0;
        }

        .class-info table {
            margin-bottom: 15px;
            font-size: 10px;
        }

        .class-info table th,
        .class-info table td {
            padding: 2px 4px;
        }

        .class-info table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .compact-cell {
            padding: 2px;
            font-size: 10px;
        }

        .name-column {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: left;
            padding-left: 5px;
        }

        .percentage-column {
            font-weight: bold;
            background-color: #f8f8f8;
        }

        .legend {
            margin-top: 10px;
            font-size: 10px;
            text-align: left;
        }

        .pertemuan-header {
            width: 25px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>REKAP KEHADIRAN MAHASISWA</h2>
    </div>

    <div class="class-info">
        <p><strong>Kelas:</strong> {{ $kelas->nama_kelas }} |
            <strong>Mata Kuliah:</strong> {{ $kelas->matkul->nama_matkul }} |
            <strong>Ruangan:</strong> {{ $kelas->ruangan->nama_ruangan }} |
            <strong>Jadwal:</strong> {{ $kelas->hari }}, {{ $kelas->waktu }}
        </p>

        <table>
            <thead>
                <tr>
                    <th class="compact-cell" style="width: 30px">No</th>
                    <th class="compact-cell" style="width: 80px">NIM</th>
                    <th class="compact-cell" style="width: 150px">Nama</th>
                    @foreach ($pertemuans as $pertemuan)
                        <th class="compact-cell pertemuan-header">
                            {{ \Carbon\Carbon::parse($pertemuan->tgl_pertemuan)->format('d/m') }}</th>
                    @endforeach
                    <th class="compact-cell" style="width: 60px">%</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendanceData as $index => $data)
                    <tr>
                        <td class="compact-cell">{{ $index + 1 }}</td>
                        <td class="compact-cell">{{ $data['student']->nim }}</td>
                        <td class="compact-cell name-column">{{ $data['student']->nama }}</td>
                        @foreach ($data['attendance'] as $status)
                            <td class="compact-cell">
                                @if ($status === 'hadir')
                                    H
                                @elseif($status === 'izin')
                                    I
                                @elseif($status === 'tidak hadir')
                                    A
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                        <td class="compact-cell percentage-column">
                            @php
                                $totalPresent = collect($data['attendance'])
                                    ->filter(fn($status) => $status === 'hadir')
                                    ->count();
                                $totalMeetings = count($data['attendance']);
                                $percentage = $totalMeetings > 0 ? round(($totalPresent / $totalMeetings) * 100) : 0;
                            @endphp
                            {{ $percentage }}%
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="legend">
            <strong>Keterangan:</strong> H = Hadir, I = Izin, A = Tidak Hadir
        </div>
    </div>



    <table style="margin-top: 5px;">
        <thead>
            <tr>
                <th class="compact-cell" style="width: 30px">P</th>
                <th class="compact-cell">Tanggal</th>
                <th class="compact-cell">Materi</th>
                <th class="compact-cell">Tipe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pertemuans as $index => $pertemuan)
                <tr>
                    <td class="compact-cell">{{ $index + 1 }}</td>
                    <td class="compact-cell">{{ \Carbon\Carbon::parse($pertemuan->tgl_pertemuan)->format('d/m/Y') }}
                    </td>
                    <td class="compact-cell" style="text-align: left; padding-left: 5px;">{{ $pertemuan->materi }}
                    </td>
                    <td class="compact-cell">{{ ucfirst($pertemuan->type_pertemuan) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
