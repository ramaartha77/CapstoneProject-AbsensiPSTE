<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Akun;
use App\Models\Krs;
use App\Models\Pertemuan;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;
use App\Models\Kelas;


class AbsensiController extends Controller
{



    // Fungsi untuk mencatat absensi (route api.php)
    public function store(Request $request)
    {

        try {
            // Validasi input UID dan macaddress
            $validatedData = $request->validate([
                'uid' => 'required|string|max:45',
                'macaddress' => 'required|string|max:255',
            ]);

            // Ambil UID dan macaddress dari request
            $uid = $request->input('uid');
            $macaddress = $validatedData['macaddress'];

            // Langkah 1: Mencocokkan UID dengan data di tabel t_akun untuk mendapatkan id_akun
            $akun = Account::where('UID', $uid)->firstOrFail();

            // Langkah 2: Cek apakah akun ini terdaftar di kelas mana saja melalui tabel t_krs
            $krs = Krs::where('id_akun', $akun->id_akun)->get();

            if ($krs->isEmpty()) {
                return response()->json(['message' => 'Akun invalid'], 404);
            }

            // Langkah 3: Cek apakah macaddress terdaftar di tabel alat_absen
            $alatAbsen = DB::table('alat_absen')->where('id_alat_absen', $macaddress)->first();

            if (!$alatAbsen) {
                return response()->json(['message' => 'Alat invalid'], 404);
            }

            // Langkah 4: Mendapatkan pertemuan yang aktif (aktivasi_absen = 1) di tabel pertemuan
            $pertemuanAktif = Pertemuan::where('aktivasi_absen', 1)
                ->whereIn('id_kelas', $krs->pluck('id_kelas'))
                ->get();

            // Langkah 5: Memeriksa apakah ada lebih dari satu pertemuan aktif
            if ($pertemuanAktif->count() > 1) {
                return response()->json(['message' => 'Konflik Jadwal'], 409);
            }

            // Langkah 6: Ambil pertemuan aktif tunggal
            $pertemuan = $pertemuanAktif->first();

            if (!$pertemuan) {
                return response()->json(['message' => 'Tidak Ada Kelas'], 404);
            }

            // Langkah 7: Cek apakah akun sudah melakukan absensi pada pertemuan ini
            $existingKehadiran = Kehadiran::where('id_akun', $akun->id_akun)
                ->where('id_pertemuan', $pertemuan->id_pertemuan)
                ->first();

            if ($existingKehadiran) {
                return response()->json(['message' => 'Anda Sudah Absen'], 409);
            }

            // Langkah 8: Catat kehadiran di tabel t_kehadiran
            $kehadiran = new Kehadiran();
            $kehadiran->id_akun = $akun->id_akun;
            $kehadiran->id_pertemuan = $pertemuan->id_pertemuan;
            $kehadiran->id_alat_absen = $alatAbsen->id_alat_absen;
            $kehadiran->status = 'hadir';
            $kehadiran->waktu_absen = now();
            $kehadiran->save();

            // Kembalikan data kehadiran untuk ditampilkan pada view
            return response()->json(['message' => 'Absensi Berhasil', 'data' => [
                'nim' => $akun->nim,
                'status' => 'hadir',
            ]], 200);
        } catch (ModelNotFoundException $e) {
            // Handle error jika tidak ada akun yang ditemukan
            return response()->json(['message' => 'Akun Invalid'], 404);
        } catch (Exception $e) {
            // Handle other generic errors
            return response()->json(['message' => 'Server Error: ' . $e->getMessage()], 500);
        }
    }
}
