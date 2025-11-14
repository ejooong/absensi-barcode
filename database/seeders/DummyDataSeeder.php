<?php
// database/seeders/DummyDataSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Petugas;
use App\Models\Peserta;
use App\Models\MataKuliah;
use App\Models\JadwalSesi;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Petugas Dummy
        $petugasData = [
            [
                'username' => 'admin',
                'password' => Hash::make('password123'),
            ],
            [
                'username' => 'operator1',
                'password' => Hash::make('operator123'),
            ],
            [
                'username' => 'petugas01',
                'password' => Hash::make('petugas123'),
            ]
        ];

        foreach ($petugasData as $data) {
            Petugas::create($data);
        }

        $this->command->info('✅ Petugas dummy created');

        // 2. Data Peserta Dummy
        $pesertaData = [
            // Kelas A
            ['nama' => 'Ahmad Santoso', 'jabatan' => 'Mahasiswa', 'kelompok' => 'A', 'kelas' => 'TI-1'],
            ['nama' => 'Budi Pratama', 'jabatan' => 'Mahasiswa', 'kelompok' => 'A', 'kelas' => 'TI-1'],
            ['nama' => 'Citra Dewi', 'jabatan' => 'Mahasiswa', 'kelompok' => 'A', 'kelas' => 'TI-1'],
            ['nama' => 'Dian Sari', 'jabatan' => 'Asisten Lab', 'kelompok' => 'A', 'kelas' => 'TI-1'],
            
            // Kelas B
            ['nama' => 'Eko Wijaya', 'jabatan' => 'Mahasiswa', 'kelompok' => 'B', 'kelas' => 'TI-2'],
            ['nama' => 'Fitriani', 'jabatan' => 'Mahasiswa', 'kelompok' => 'B', 'kelas' => 'TI-2'],
            ['nama' => 'Gunawan', 'jabatan' => 'Koordinator', 'kelompok' => 'B', 'kelas' => 'TI-2'],
            
            // Kelas C
            ['nama' => 'Hendra Kurniawan', 'jabatan' => 'Mahasiswa', 'kelompok' => 'C', 'kelas' => 'SI-1'],
            ['nama' => 'Indah Permata', 'jabatan' => 'Mahasiswa', 'kelompok' => 'C', 'kelas' => 'SI-1'],
            ['nama' => 'Joko Susilo', 'jabatan' => 'Asisten Dosen', 'kelompok' => 'C', 'kelas' => 'SI-1'],
        ];

        foreach ($pesertaData as $data) {
            $barcodeData = 'ABS-' . time() . '-' . rand(1000, 9999) . '-' . substr(md5($data['nama'] . rand(1000, 9999)), 0, 8);
            
            Peserta::create([
                'nama' => $data['nama'],
                'jabatan' => $data['jabatan'],
                'kelompok' => $data['kelompok'],
                'kelas' => $data['kelas'],
                'barcode_data' => $barcodeData
            ]);
        }

        $this->command->info('✅ Peserta dummy created');

        // 3. Data Mata Kuliah Dummy
        $mataKuliahData = [
            ['nama_materi' => 'Pemrograman Web', 'kode_materi' => 'PW-001'],
            ['nama_materi' => 'Basis Data', 'kode_materi' => 'BD-002'],
            ['nama_materi' => 'Algoritma Pemrograman', 'kode_materi' => 'AP-003'],
            ['nama_materi' => 'Jaringan Komputer', 'kode_materi' => 'JK-004'],
        ];

        foreach ($mataKuliahData as $data) {
            MataKuliah::create($data);
        }

        $this->command->info('✅ Mata kuliah dummy created');

        // 4. Data Jadwal Sesi Dummy - buat untuk waktu mendatang
        $jadwalSesiData = [
            [
                'mata_kuliah_id' => 1,
                'sesi_ke' => 1,
                'materi' => 'Pengenalan HTML & CSS',
                'waktu_mulai' => Carbon::now()->addHours(1), // 1 jam dari sekarang
                'waktu_akhir' => Carbon::now()->addHours(3), // 3 jam dari sekarang
                'created_by' => 1
            ],
            [
                'mata_kuliah_id' => 1,
                'sesi_ke' => 2,
                'materi' => 'JavaScript Dasar',
                'waktu_mulai' => Carbon::tomorrow()->setTime(8, 0, 0),
                'waktu_akhir' => Carbon::tomorrow()->setTime(10, 0, 0),
                'created_by' => 1
            ],
            [
                'mata_kuliah_id' => 2,
                'sesi_ke' => 1,
                'materi' => 'Konsep Basis Data',
                'waktu_mulai' => Carbon::tomorrow()->addDay()->setTime(13, 0, 0),
                'waktu_akhir' => Carbon::tomorrow()->addDay()->setTime(15, 0, 0),
                'created_by' => 1
            ],
        ];

        foreach ($jadwalSesiData as $data) {
            JadwalSesi::create($data);
        }

        $this->command->info('✅ Jadwal sesi dummy created');

        $this->command->info('🎉 SEMUA DATA DUMMY BERHASIL DIBUAT!');
        $this->command->info('📋 Credentials Login:');
        $this->command->info('   - admin / password123');
        $this->command->info('   - operator1 / operator123');
        $this->command->info('   - petugas01 / petugas123');
        $this->command->info('👥 Total: 3 petugas, 10 peserta, 4 mata kuliah, 3 jadwal sesi');
    }
}