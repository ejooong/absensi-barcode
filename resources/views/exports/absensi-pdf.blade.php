<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi - {{ $tanggal }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 12px;
            line-height: 1.4;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 { 
            margin: 0; 
            color: #333;
            font-size: 24px;
        }
        .header p { 
            margin: 5px 0; 
            color: #666;
        }
        .summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }
        .summary-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .summary-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .summary-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        .table th { 
            background-color: #343a40; 
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
        }
        .table td { 
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
        }
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-hadir { 
            color: #28a745; 
            font-weight: bold;
        }
        .status-terlambat { 
            color: #dc3545; 
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .filter-info {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ABSENSI</h1>
        <p>Sistem Absensi Barcode</p>
        <p>Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
    </div>

    @if($kelas) {{-- HAPUS KELOMPOK DARI FILTER --}}
    <div class="filter-info">
        <strong>Filter:</strong>
        Kelas: {{ $kelas }}
    </div>
    @endif

    <div class="summary">
        <strong>Ringkasan Absensi:</strong>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number">{{ $totalAbsensi }}</div>
                <div class="summary-label">Total Absensi</div>
            </div>
            <div class="summary-item">
                <div class="summary-number" style="color: #28a745;">{{ $totalHadir }}</div>
                <div class="summary-label">Hadir Tepat Waktu</div>
            </div>
            <div class="summary-item">
                <div class="summary-number" style="color: #dc3545;">{{ $totalTerlambat }}</div>
                <div class="summary-label">Terlambat</div>
            </div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peserta</th>
                <th>Jabatan</th>
                {{-- HAPUS KOLOM KELOMPOK --}}
                <th>Kelas</th>
                <th>Mata Kuliah</th>
                <th>Sesi</th>
                <th>Waktu Absen</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->peserta->nama }}</td>
                <td>{{ $item->peserta->jabatan ?? '-' }}</td>
                {{-- HAPUS KOLOM KELOMPOK --}}
                <td>{{ $item->peserta->kelas ?? '-' }}</td>
                <td>{{ $item->jadwalSesi->mataKuliah->nama_materi }}</td>
                <td>Sesi {{ $item->jadwalSesi->sesi_ke }}</td>
                <td>{{ $item->waktu_absen->format('H:i:s') }}</td>
                <td class="status-{{ $item->status }}">
                    {{ strtoupper($item->status) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }} |
        Total Data: {{ $absensi->count() }} record
    </div>
</body>
</html>