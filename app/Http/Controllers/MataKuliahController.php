<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;

class MataKuliahController extends Controller
{
    public function index()
    {
        $mataKuliah = MataKuliah::latest()->paginate(10);
        
        return view('mata-kuliah.index', compact('mataKuliah'));
    }

    public function create()
    {
        return view('mata-kuliah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_materi' => 'required|string|max:255|unique:mata_kuliah,nama_materi',
            'kode_materi' => 'required|string|max:50|unique:mata_kuliah,kode_materi'
        ]);

        MataKuliah::create($request->all());

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    public function show(MataKuliah $mataKuliah)
    {
        $mataKuliah->load('jadwalSesi');
        return view('mata-kuliah.show', compact('mataKuliah'));
    }

    public function edit(MataKuliah $mataKuliah)
    {
        return view('mata-kuliah.edit', compact('mataKuliah'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $request->validate([
            'nama_materi' => 'required|string|max:255|unique:mata_kuliah,nama_materi,' . $mataKuliah->id,
            'kode_materi' => 'required|string|max:50|unique:mata_kuliah,kode_materi,' . $mataKuliah->id
        ]);

        $mataKuliah->update($request->all());

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil diupdate!');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        // Cek apakah mata kuliah sudah digunakan di jadwal
        if ($mataKuliah->jadwalSesi()->exists()) {
            return redirect()->route('mata-kuliah.index')
                ->with('error', 'Tidak bisa menghapus mata kuliah yang sudah digunakan di jadwal!');
        }

        $mataKuliah->delete();

        return redirect()->route('mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus!');
    }
}