<?php
// app/Exports/AbsensiExport.php
namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $tanggal;
    protected $kelas; 

    public function __construct($tanggal = null, $kelas = null)
    {
        $this->tanggal = $tanggal ?: Carbon::today()->format('Y-m-d');
        $this->kelas = $kelas;
    }

    public function collection()
    {
        // PERBAIKAN: Update relasi ke model yang baru
        $query = Absensi::with(['peserta', 'kegiatan.program'])
            ->whereDate('waktu_absen', $this->tanggal);

        if ($this->kelas) {
            $query->whereHas('peserta', function($q) {
                $q->where('kelas', $this->kelas);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Peserta',
            'Jabatan',
            'Kelas', 
            'Program', // PERBAIKAN: Ganti dari 'Mata Kuliah'
            'Sesi',
            'Materi',
            'Waktu Absen',
            'Status',
            'Tanggal'
        ];
    }

    public function map($absensi): array
    {
        // PERBAIKAN: Update mapping ke relasi yang baru
        return [
            $absensi->peserta->nama,
            $absensi->peserta->jabatan ?? '-',
            $absensi->peserta->kelas ?? '-',
            $absensi->kegiatan->program->nama_materi, // PERBAIKAN: Ganti relasi
            'Sesi ' . $absensi->kegiatan->sesi_ke, // PERBAIKAN: Ganti relasi
            $absensi->kegiatan->materi, // PERBAIKAN: Ganti relasi
            $absensi->waktu_absen->format('H:i:s'),
            $absensi->status,
            $absensi->waktu_absen->format('d/m/Y')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            
            // Style the header row
            'A1:I1' => [ 
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFE6E6FA']
                ]
            ],
        ];
    }
}