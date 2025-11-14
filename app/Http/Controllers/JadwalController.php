<?php
// app/Http/Controllers/JadwalController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalSesi;
use App\Models\MataKuliah;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = JadwalSesi::with(['mataKuliah', 'petugas', 'absensi'])
            ->latest()
            ->paginate(10);

        // Hitung dari SEMUA data di database (bukan hanya pagination)
        $now = Carbon::now();
        $totalJadwal = JadwalSesi::count();
        $jadwalAktif = JadwalSesi::where('waktu_mulai', '<=', $now)
            ->where('waktu_akhir', '>=', $now)
            ->count();
        $jadwalAkanDatang = JadwalSesi::where('waktu_mulai', '>', $now)->count();
        $jadwalSelesai = JadwalSesi::where('waktu_akhir', '<', $now)->count();

        return view('jadwal.index', compact(
            'jadwal', 
            'totalJadwal', 
            'jadwalAktif', 
            'jadwalAkanDatang', 
            'jadwalSelesai'
        ));
    }

    public function create()
    {
        $mataKuliah = MataKuliah::all();
        return view('jadwal.create', compact('mataKuliah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'sesi_ke' => 'required|integer|min:1',
            'materi' => 'required|string|max:255',
            'waktu_mulai' => 'required|date',
            'waktu_akhir' => 'required|date|after:waktu_mulai'
        ]);

        // Cek apakah ada jadwal yang bentrok
        $bentrok = JadwalSesi::where(function($query) use ($request) {
            $query->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_akhir])
                  ->orWhereBetween('waktu_akhir', [$request->waktu_mulai, $request->waktu_akhir])
                  ->orWhere(function($q) use ($request) {
                      $q->where('waktu_mulai', '<=', $request->waktu_mulai)
                        ->where('waktu_akhir', '>=', $request->waktu_akhir);
                  });
        })->exists();

        if ($bentrok) {
            return redirect()->back()->withErrors(['waktu_mulai' => 'Jadwal bentrok dengan sesi lain!'])->withInput();
        }

        JadwalSesi::create([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'sesi_ke' => $request->sesi_ke,
            'materi' => $request->materi,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_akhir' => $request->waktu_akhir,
            'created_by' => auth()->guard('petugas')->id()
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function show(JadwalSesi $jadwal)
    {
        $jadwal->load(['mataKuliah', 'petugas', 'absensi.peserta']);
        return view('jadwal.show', compact('jadwal'));
    }

    public function edit(JadwalSesi $jadwal)
    {
        $mataKuliah = MataKuliah::all();
        return view('jadwal.edit', compact('jadwal', 'mataKuliah'));
    }

    public function update(Request $request, JadwalSesi $jadwal)
    {
        $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'sesi_ke' => 'required|integer|min:1',
            'materi' => 'required|string|max:255',
            'waktu_mulai' => 'required|date',
            'waktu_akhir' => 'required|date|after:waktu_mulai'
        ]);

        // Cek bentrok (kecuali dengan dirinya sendiri)
        $bentrok = JadwalSesi::where('id', '!=', $jadwal->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_akhir])
                      ->orWhereBetween('waktu_akhir', [$request->waktu_mulai, $request->waktu_akhir])
                      ->orWhere(function($q) use ($request) {
                          $q->where('waktu_mulai', '<=', $request->waktu_mulai)
                            ->where('waktu_akhir', '>=', $request->waktu_akhir);
                      });
            })->exists();

        if ($bentrok) {
            return redirect()->back()->withErrors(['waktu_mulai' => 'Jadwal bentrok dengan sesi lain!'])->withInput();
        }

        $jadwal->update([
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'sesi_ke' => $request->sesi_ke,
            'materi' => $request->materi,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_akhir' => $request->waktu_akhir
        ]);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate!');
    }

    public function destroy(JadwalSesi $jadwal)
    {
        // Cek apakah sudah ada absensi
        if ($jadwal->absensi()->exists()) {
            return redirect()->route('jadwal.index')->with('error', 'Tidak bisa menghapus jadwal yang sudah ada absensinya!');
        }

        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }

    // API untuk mendapatkan jadwal aktif
    public function getJadwalAktif()
    {
        $now = Carbon::now();
        
        $jadwalAktif = JadwalSesi::where('waktu_mulai', '<=', $now)
            ->where('waktu_akhir', '>=', $now)
            ->with('mataKuliah')
            ->first();

        $jadwalAkanDatang = JadwalSesi::where('waktu_mulai', '>', $now)
            ->orderBy('waktu_mulai')
            ->with('mataKuliah')
            ->first();

        return response()->json([
            'jadwal_aktif' => $jadwalAktif,
            'jadwal_berikutnya' => $jadwalAkanDatang,
            'waktu_sekarang' => $now->format('Y-m-d H:i:s'),
            'timestamp' => $now->timestamp
        ]);
    }
}