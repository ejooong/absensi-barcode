<?php
// app/Imports/PesertaImport.php
namespace App\Imports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PesertaImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $updateExisting;

    public function __construct($updateExisting = false)
    {
        $this->updateExisting = $updateExisting;
    }

    public function model(array $row)
    {
        // Skip if nama is empty
        if (empty($row['nama'])) {
            return null;
        }

        // Generate unique barcode
        $barcodeData = 'ABS-' . time() . '-' . Str::random(8);

        $data = [
            'nama' => $row['nama'],
            'jabatan' => $row['jabatan'] ?? null,
           
            'kelas' => $row['kelas'] ?? null,
            'barcode_data' => $barcodeData
        ];

        if ($this->updateExisting) {
            // Update if exists, otherwise create
            return Peserta::updateOrCreate(
                ['nama' => $row['nama']],
                $data
            );
        } else {
            // Only create new
            return new Peserta($data);
        }
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
         
            'kelas' => 'nullable|string|max:50'
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Kolom nama wajib diisi',
            'nama.string' => 'Kolom nama harus berupa teks',
        ];
    }
}