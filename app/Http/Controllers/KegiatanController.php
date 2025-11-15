<?php
// app/Http/Controllers/KegiatanController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan; // Ganti dari JadwalSesi
use App\Models\Program; // Ganti dari MataKuliah
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::with(['program', 'petugas', 'absensi']) // Ganti dari mataKuliah
            ->latest()
            ->paginate(10);

        // Hitung dari SEMUA data di database (bukan hanya pagination)
        $now = Carbon::now();
        $totalKegiatan = Kegiatan::count();
        $kegiatanAktif = Kegiatan::where('waktu_mulai', '<=', $now)
            ->where('waktu_akhir', '>=', $now)
            ->count();
        $kegiatanAkanDatang = Kegiatan::where('waktu_mulai', '>', $now)->count();
        $kegiatanSelesai = Kegiatan::where('waktu_akhir', '<', $now)->count();

        return view('kegiatan.index', compact( // Ganti view ke kegiatan
            'kegiatan', 
            'totalKegiatan', // Ganti dari totalJadwal
            'kegiatanAktif', // Ganti dari jadwalAktif
            'kegiatanAkanDatang', // Ganti dari jadwalAkanDatang
            'kegiatanSelesai' // Ganti dari jadwalSelesai
        ));
    }

    public function create()
    {
        $program = Program::all(); // Ganti dari MataKuliah
        return view('kegiatan.create', compact('program')); // Ganti view ke kegiatan
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:program,id', // Ganti dari mata_kuliah_id
            'sesi_ke' => 'required|integer|min:1',
            'materi' => 'required|string|max:255',
            'waktu_mulai' => 'required|date',
            'waktu_akhir' => 'required|date|after:waktu_mulai'
        ]);

        // Cek apakah ada kegiatan yang bentrok
        $bentrok = Kegiatan::where(function($query) use ($request) {
            $query->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_akhir])
                  ->orWhereBetween('waktu_akhir', [$request->waktu_mulai, $request->waktu_akhir])
                  ->orWhere(function($q) use ($request) {
                      $q->where('waktu_mulai', '<=', $request->waktu_mulai)
                        ->where('waktu_akhir', '>=', $request->waktu_akhir);
                  });
        })->exists();

        if ($bentrok) {
            return redirect()->back()->withErrors(['waktu_mulai' => 'Kegiatan bentrok dengan sesi lain!'])->withInput();
        }

        Kegiatan::create([
            'program_id' => $request->program_id, // Ganti dari mata_kuliah_id
            'sesi_ke' => $request->sesi_ke,
            'materi' => $request->materi,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_akhir' => $request->waktu_akhir,
            'created_by' => auth()->guard('petugas')->id()
        ]);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan!'); // Ganti route
    }

    public function show(Kegiatan $kegiatan) // Ganti parameter
    {
        $kegiatan->load(['program', 'petugas', 'absensi.peserta']); // Ganti dari mataKuliah
        return view('kegiatan.show', compact('kegiatan')); // Ganti view
    }

    public function edit(Kegiatan $kegiatan) // Ganti parameter
    {
        $program = Program::all(); // Ganti dari MataKuliah
        return view('kegiatan.edit', compact('kegiatan', 'program')); // Ganti view
    }

    public function update(Request $request, Kegiatan $kegiatan) // Ganti parameter
    {
        $request->validate([
            'program_id' => 'required|exists:program,id', // Ganti dari mata_kuliah_id
            'sesi_ke' => 'required|integer|min:1',
            'materi' => 'required|string|max:255',
            'waktu_mulai' => 'required|date',
            'waktu_akhir' => 'required|date|after:waktu_mulai'
        ]);

        // Cek bentrok (kecuali dengan dirinya sendiri)
        $bentrok = Kegiatan::where('id', '!=', $kegiatan->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_akhir])
                      ->orWhereBetween('waktu_akhir', [$request->waktu_mulai, $request->waktu_akhir])
                      ->orWhere(function($q) use ($request) {
                          $q->where('waktu_mulai', '<=', $request->waktu_mulai)
                            ->where('waktu_akhir', '>=', $request->waktu_akhir);
                      });
            })->exists();

        if ($bentrok) {
            return redirect()->back()->withErrors(['waktu_mulai' => 'Kegiatan bentrok dengan sesi lain!'])->withInput();
        }

        $kegiatan->update([
            'program_id' => $request->program_id, // Ganti dari mata_kuliah_id
            'sesi_ke' => $request->sesi_ke,
            'materi' => $request->materi,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_akhir' => $request->waktu_akhir
        ]);

        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil diupdate!'); // Ganti route
    }

    public function destroy(Kegiatan $kegiatan) // Ganti parameter
    {
        // Cek apakah sudah ada absensi
        if ($kegiatan->absensi()->exists()) {
            return redirect()->route('kegiatan.index')->with('error', 'Tidak bisa menghapus kegiatan yang sudah ada absensinya!'); // Ganti route
        }

        $kegiatan->delete();
        return redirect()->route('kegiatan.index')->with('success', 'Kegiatan berhasil dihapus!'); // Ganti route
    }

    // API untuk mendapatkan kegiatan aktif
    public function getKegiatanAktif() // Ganti nama method
    {
        $now = Carbon::now();
        
        $kegiatanAktif = Kegiatan::where('waktu_mulai', '<=', $now)
            ->where('waktu_akhir', '>=', $now)
            ->with('program') // Ganti dari mataKuliah
            ->first();

        $kegiatanAkanDatang = Kegiatan::where('waktu_mulai', '>', $now)
            ->orderBy('waktu_mulai')
            ->with('program') // Ganti dari mataKuliah
            ->first();

        return response()->json([
            'kegiatan_aktif' => $kegiatanAktif, // Ganti key
            'kegiatan_berikutnya' => $kegiatanAkanDatang, // Ganti key
            'waktu_sekarang' => $now->format('Y-m-d H:i:s'),
            'timestamp' => $now->timestamp
        ]);
    }
}