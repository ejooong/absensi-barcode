<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Peserta - {{ $exportDate }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 10px;
            line-height: 1.3;
        }
        .header { 
            text-align: center; 
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 { 
            margin: 0; 
            color: #333;
            font-size: 20px;
        }
        .header p { 
            margin: 3px 0; 
            color: #666;
        }
        .summary {
            background: #28a745;
            color: white;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
            font-size: 9px;
        }
        .table th { 
            background-color: #343a40; 
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        .table td { 
            padding: 6px 4px;
            border: 1px solid #ddd;
            vertical-align: middle;
        }
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .barcode {
            font-family: 'Courier New', monospace;
            font-size: 8px;
            color: #666;
        }
        .qr-code {
            text-align: center;
        }
        .qr-code img {
            width: 60px;
            height: 60px;
            border: 1px solid #ddd;
        }
        .no-qr {
            text-align: center;
            color: #999;
            font-style: italic;
            font-size: 8px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
            font-size: 8px;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        {{-- HAPUS STYLE KELOMPOK --}}
        .text-center { text-align: center; }
        .text-small { font-size: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>DATA PESERTA DAN BARCODE</h1>
        <p>Sistem Absensi Barcode</p>
        <p>Tanggal Export: {{ $exportDate }}</p>
    </div>

    <div class="summary">
        TOTAL PESERTA: {{ $totalPeserta }} ORANG
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="18%">Nama Lengkap</th>
                <th width="12%">Jabatan</th>
                {{-- HAPUS KOLOM KELOMPOK --}}
                <th width="10%">Kelas</th>
                <th width="18%">Kode Barcode</th>
                <th width="14%">QR Code</th>
                <th width="35%">Cara Scan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peserta as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $item->nama }}</strong></td>
                <td>{{ $item->jabatan ?? '-' }}</td>
                {{-- HAPUS KOLOM KELOMPOK --}}
                <td class="text-center">{{ $item->kelas ?? '-' }}</td>
                <td class="barcode">{{ $item->barcode_data }}</td>
                <td class="qr-code">
                    @if($item->qr_code_base64)
                        <img src="{{ $item->qr_code_base64 }}" alt="QR Code">
                    @else
                        <div class="no-qr">
                            <i class="fas fa-exclamation-triangle"></i><br>
                            QR tidak tersedia
                        </div>
                    @endif
                </td>
                <td class="text-small">
                    <strong>Scan dengan:</strong><br>
                    1. Buka Aplikasi Scanner<br>
                    2. Arahkan kamera ke QR Code<br>
                    3. Tunggu konfirmasi absensi<br>
                    <em>Kode: {{ $item->barcode_data }}</em>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d F Y H:i:s') }} | 
        Total Data: {{ $peserta->count() }} peserta |
        Dokumen ini berisi informasi sensitif - Harap disimpan dengan aman
    </div>
</body>
</html>