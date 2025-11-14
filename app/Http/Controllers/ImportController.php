<?php
// app/Http/Controllers/ImportController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\PesertaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function importPeserta(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240' // 10MB max
        ]);

        try {
            $updateExisting = $request->has('update_existing');
            
            Excel::import(new PesertaImport($updateExisting), $request->file('file'));
            
            return redirect()->route('export.form')->with('success', 'Data peserta berhasil diimport!');
            
        } catch (\Exception $e) {
            return redirect()->route('export.form')->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        
        $templateData = [
            ['Nama', 'Jabatan', 'Kelas'],
            ['Vyco Kentung', 'Mahasiswa', 'TI-1'],
            ['Vyco Lutung', 'Asisten Lab', 'TI-2'],
            ['Vyco nich', 'Mahasiswa', 'SI-1'],
        ];

        $export = new class($templateData) implements \Maatwebsite\Excel\Concerns\FromArray {
            protected $data;
            
            public function __construct($data)
            {
                $this->data = $data;
            }
            
            public function array(): array
            {
                return $this->data;
            }
        };

        return Excel::download($export, 'template-import-peserta.xlsx');
    }
}