<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Rename table
        Schema::rename('mata_kuliah', 'program');
        
        // Update foreign key constraints
        Schema::table('jadwal_sesi', function (Blueprint $table) {
            $table->dropForeign(['mata_kuliah_id']);
            $table->renameColumn('mata_kuliah_id', 'program_id');
        });
    }

    public function down()
    {
        // Revert foreign key changes
        Schema::table('jadwal_sesi', function (Blueprint $table) {
            $table->renameColumn('program_id', 'mata_kuliah_id');
            $table->foreign('mata_kuliah_id')->references('id')->on('mata_kuliah')->onDelete('cascade');
        });
        
        // Revert table name
        Schema::rename('program', 'mata_kuliah');
    }
};