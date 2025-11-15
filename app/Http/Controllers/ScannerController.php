<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScannerController extends Controller
{
    public function index()
    {
        return view('scanner');
    }

    public function process(Request $request)
    {
        try {
            $barcodeData = $request->input('barcode_data');
            
            // Log untuk debugging
            Log::info('QR Code Scanned:', ['barcode' => $barcodeData]);
            
            // Validasi input
            if (!$barcodeData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data barcode tidak valid'
                ], 400);
            }
            
            // Parse barcode data (contoh format: ABS-1763134302-jaqKlw4c)
            $barcodeParts = explode('-', $barcodeData);
            
            if (count($barcodeParts) < 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format barcode tidak valid'
                ], 400);
            }
            
            $prefix = $barcodeParts[0]; // ABS
            $userId = $barcodeParts[1]; // 1763134302
            $token = $barcodeParts[2]; // jaqKlw4c
            
            // TODO: Implementasi logic absensi sesuai kebutuhan
            // Contoh: cek di database, validasi user, dll.
            
            // Contoh response sukses
            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dicatat',
                'data' => [
                    'nama' => 'User ' . $userId, // Ganti dengan nama dari database
                    'program' => 'Training Program', // Ganti dengan program dari database
                    'sesi' => 'Sesi 1',
                    'status' => 'hadir',
                    'waktu' => now()->format('H:i:s'),
                    'tanggal' => now()->translatedFormat('d F Y') // Format Indonesia
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error processing barcode:', [
                'message' => $e->getMessage(),
                'barcode' => $request->input('barcode_data')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}