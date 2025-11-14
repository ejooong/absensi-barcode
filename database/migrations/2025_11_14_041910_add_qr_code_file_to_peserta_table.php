<?php
// database/migrations/2024_11_14_xxxxxx_add_qr_code_file_to_peserta_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQrCodeFileToPesertaTable extends Migration
{
    public function up()
    {
        Schema::table('peserta', function (Blueprint $table) {
            $table->string('qr_code_file')->nullable()->after('barcode_data');
        });
    }

    public function down()
    {
        Schema::table('peserta', function (Blueprint $table) {
            $table->dropColumn('qr_code_file');
        });
    }
}