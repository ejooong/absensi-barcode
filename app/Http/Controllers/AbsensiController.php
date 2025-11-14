<?php
// app/Http/Controllers/AbsensiController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\JadwalSesi;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    // Scanner untuk public (tanpa login)
    public function scannerPublic()
    {
        // Cari jadwal yang aktif sekarang
        $now = Carbon::now();
        $jadwalAktif = JadwalSesi::where('waktu_mulai', '<=', $now)
            ->where('waktu_akhir', '>=', $now)
            ->with('mataKuliah')
            ->first();

        return view('scanner-public', compact('jadwalAktif'));
    }

    // Scanner untuk admin (dengan login)
    public function scanner()
    {
        // Cari jadwal yang aktif sekarang
        $now = Carbon::now();
        $jadwalAktif = JadwalSesi::where('waktu_mulai', '<=', $now)
            ->where('waktu_akhir', '>=', $now)
            ->with('mataKuliah')
            ->first();

        return view('absensi.scanner', compact('jadwalAktif'));
    }

    public function processAbsensi(Request $request)
    {
        $request->validate([
            'barcode_data' => 'required'
        ]);

        try {
            // Cari peserta berdasarkan barcode
            $peserta = Peserta::where('barcode_data', $request->barcode_data)->first();

            if (!$peserta) {
                return response()->json([
                    'success' => false,
                    'message' => 'Barcode tidak valid! Peserta tidak ditemukan.'
                ], 404);
            }

            // Cari jadwal sesi yang aktif sekarang
            $now = Carbon::now();
            $jadwalSesi = JadwalSesi::where('waktu_mulai', '<=', $now)
                ->where('waktu_akhir', '>=', $now)
                ->with('mataKuliah')
                ->first();

            if (!$jadwalSesi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada jadwal sesi yang aktif saat ini!'
                ], 400);
            }

            // Cek apakah sudah absen - SEKARANG AMBIL DATA ABSENSI YANG SUDAH ADA
            $absensiTerdaftar = Absensi::where('peserta_id', $peserta->id)
                ->where('jadwal_sesi_id', $jadwalSesi->id)
                ->first();

            if ($absensiTerdaftar) {
                // Kembalikan data absensi yang sudah ada
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah absen untuk sesi ini!',
                    'data' => [
                        'nama' => $peserta->nama,
                        'jabatan' => $peserta->jabatan,
                        'kelas' => $peserta->kelas,
                        'sesi' => $jadwalSesi->materi,
                        'mata_kuliah' => $jadwalSesi->mataKuliah->nama_materi,
                        'status' => $absensiTerdaftar->status,
                        'waktu' => $absensiTerdaftar->waktu_absen->format('H:i:s'),
                        'tanggal' => $absensiTerdaftar->waktu_absen->format('d M Y')
                    ]
                ], 400);
            }

            // Tentukan status (terlambat atau hadir)
            $status = 'hadir';
            $toleransiMenit = 15; // Toleransi keterlambatan 15 menit
            $waktuMulai = Carbon::parse($jadwalSesi->waktu_mulai);
            
            if ($now->diffInMinutes($waktuMulai, false) < -$toleransiMenit) {
                $status = 'terlambat';
            }

            // Simpan absensi
            $absensi = Absensi::create([
                'peserta_id' => $peserta->id,
                'jadwal_sesi_id' => $jadwalSesi->id,
                'waktu_absen' => $now,
                'status' => $status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil!',
                'data' => [
                    'nama' => $peserta->nama,
                    'jabatan' => $peserta->jabatan,
                    'kelas' => $peserta->kelas,
                    'sesi' => $jadwalSesi->materi,
                    'mata_kuliah' => $jadwalSesi->mataKuliah->nama_materi,
                    'status' => $status,
                    'waktu' => $now->format('H:i:s'),
                    'tanggal' => $now->format('d M Y')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function riwayat()
    {
        $absensi = Absensi::with(['peserta', 'jadwalSesi.mataKuliah'])
            ->latest()
            ->paginate(20);

        // Filter options - HAPUS KELOMPOK
        $kelasList = Peserta::distinct()->whereNotNull('kelas')->pluck('kelas');

        return view('absensi.riwayat', compact('absensi', 'kelasList'));
    }

    public function filterRiwayat(Request $request)
    {
        $query = Absensi::with(['peserta', 'jadwalSesi.mataKuliah']);

        // Filter by date
        if ($request->has('tanggal') && $request->tanggal) {
            $query->whereDate('waktu_absen', $request->tanggal);
        }

        // Filter by peserta name
        if ($request->has('nama') && $request->nama) {
            $query->whereHas('peserta', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }

        // HAPUS FILTER KELOMPOK

        // Filter by kelas
        if ($request->has('kelas') && $request->kelas) {
            $query->whereHas('peserta', function($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        $absensi = $query->latest()->paginate(20);

        // HAPUS KELOMPOK LIST
        $kelasList = Peserta::distinct()->whereNotNull('kelas')->pluck('kelas');

        return view('absensi.riwayat', compact('absensi', 'kelasList'));
    }

    // API untuk mendapatkan jadwal aktif
    public function getJadwalAktif()
    {
        $now = Carbon::now();
        $jadwalAktif = JadwalSesi::where('waktu_mulai', '<=', $now)
            ->where('waktu_akhir', '>=', $now)
            ->with('mataKuliah')
            ->first();

        return response()->json([
            'jadwal_aktif' => $jadwalAktif,
            'waktu_sekarang' => $now->format('Y-m-d H:i:s')
        ]);
    }

    protected function processBarcodeText(string $barcodeText)
    {
        // validasi & cari peserta
        $peserta = Peserta::where('barcode_data', $barcodeText)->first();

        if (!$peserta) {
            return response()->json([
                'success' => false, 'message' => 'Barcode tidak valid! Peserta tidak ditemukan.'
            ], 404);
        }

        $now = Carbon::now();
        $jadwalSesi = JadwalSesi::where('waktu_mulai', '<=', $now)
            ->where('waktu_akhir', '>=', $now)
            ->with('mataKuliah')
            ->first();

        if (!$jadwalSesi) {
            return response()->json([
                'success' => false, 'message' => 'Tidak ada jadwal sesi yang aktif saat ini!'
            ], 400);
        }

        // Cek apakah sudah absen - AMBIL DATA YANG SUDAH ADA
        $absensiTerdaftar = Absensi::where('peserta_id', $peserta->id)
            ->where('jadwal_sesi_id', $jadwalSesi->id)
            ->first();

        if ($absensiTerdaftar) {
            return response()->json([
                'success' => false, 
                'message' => 'Anda sudah absen untuk sesi ini!',
                'data' => [
                    'nama' => $peserta->nama,
                    'jabatan' => $peserta->jabatan,
                    'kelas' => $peserta->kelas,
                    'sesi' => $jadwalSesi->materi,
                    'mata_kuliah' => $jadwalSesi->mataKuliah->nama_materi,
                    'status' => $absensiTerdaftar->status,
                    'waktu' => $absensiTerdaftar->waktu_absen->format('H:i:s'),
                    'tanggal' => $absensiTerdaftar->waktu_absen->format('d M Y')
                ]
            ], 400);
        }

        $status = 'hadir';
        $toleransiMenit = 15;
        $waktuMulai = Carbon::parse($jadwalSesi->waktu_mulai);
        if ($now->diffInMinutes($waktuMulai, false) < -$toleransiMenit) {
            $status = 'terlambat';
        }

        $absensi = Absensi::create([
            'peserta_id' => $peserta->id,
            'jadwal_sesi_id' => $jadwalSesi->id,
            'waktu_absen' => $now,
            'status' => $status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil!',
            'data' => [
                'nama' => $peserta->nama,
                'jabatan' => $peserta->jabatan,
                'kelas' => $peserta->kelas,
                'sesi' => $jadwalSesi->materi,
                'mata_kuliah' => $jadwalSesi->mataKuliah->nama_materi,
                'status' => $status,
                'waktu' => $now->format('H:i:s'),
                'tanggal' => $now->format('d M Y')
            ]
        ]);
    }

    /**
     * Endpoint to decode uploaded image at server side (optional fallback)
     */
    public function decodeUpload(Request $request)
    {
        $request->validate(['image' => 'required|image|max:5120']); // 5MB

        $file = $request->file('image');
        $path = $file->store('uploads', 'local'); // storage/app/uploads/...
        $fullPath = storage_path('app/' . $path);

        try {
            // Menggunakan Simple-QRCode untuk decode
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::decode($fullPath);
            $text = $qrCode ? $qrCode->getText() : null;

            if (!$text) {
                return response()->json(['success' => false, 'message' => 'QR tidak terbaca pada gambar.'], 422);
            }

            // gunakan reuse helper untuk proses absensi dari teks
            return $this->processBarcodeText($text);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal decode: '.$e->getMessage()], 500);
        } finally {
            // opsional: hapus file yang diupload
            Storage::disk('local')->delete($path);
        }
    }
}