<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program; // Ganti dari MataKuliah

class ProgramController extends Controller
{
    public function index()
    {
        $program = Program::latest()->paginate(10); // Ganti variable
        return view('program.index', compact('program')); // Ganti view
    }

    public function create()
    {
        return view('program.create'); // Ganti view
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_materi' => 'required|string|max:255|unique:program,nama_materi' // Ganti tabel
        ]);

        // Hanya simpan nama_materi saja
        Program::create([ // Ganti model
            'nama_materi' => $request->nama_materi
        ]);

        return redirect()->route('program.index') // Ganti route
            ->with('success', 'Program berhasil ditambahkan!'); // Ganti pesan
    }

    public function show(Program $program) // Ganti parameter
    {
        $program->load('kegiatan'); // Ganti relasi
        return view('program.show', compact('program')); // Ganti view
    }

    public function edit(Program $program) // Ganti parameter
    {
        return view('program.edit', compact('program')); // Ganti view
    }

    public function update(Request $request, Program $program) // Ganti parameter
    {
        $request->validate([
            'nama_materi' => 'required|string|max:255|unique:program,nama_materi,' . $program->id // Ganti tabel
        ]);

        $program->update([
            'nama_materi' => $request->nama_materi
        ]);

        return redirect()->route('program.index') // Ganti route
            ->with('success', 'Program berhasil diupdate!'); // Ganti pesan
    }

    public function destroy(Program $program) // Ganti parameter
    {
        if ($program->kegiatan()->exists()) { // Ganti relasi
            return redirect()->route('program.index') // Ganti route
                ->with('error', 'Tidak bisa menghapus program yang sudah digunakan di kegiatan!'); // Ganti pesan
        }

        $program->delete();

        return redirect()->route('program.index') // Ganti route
            ->with('success', 'Program berhasil dihapus!'); // Ganti pesan
    }
}