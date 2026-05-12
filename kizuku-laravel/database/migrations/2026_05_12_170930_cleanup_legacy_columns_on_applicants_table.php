<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'tinggi_badan', 
                'berat_badan', 
                'kondisi_mata', 
                'tato', 
                'merokok', 
                'bidang_ssw', 
                'level_bahasa_jepang', 
                'ipk', 
                'shift_kursus',
                'jurusan_ipk', 
                'pengalaman', 
                'motivasi'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            // Re-adding the columns just in case of rollback
            $table->integer('tinggi_badan')->nullable();
            $table->integer('berat_badan')->nullable();
            $table->string('kondisi_mata')->nullable();
            $table->boolean('tato')->default(false);
            $table->boolean('merokok')->default(false);
            $table->string('bidang_ssw')->nullable();
            $table->string('level_bahasa_jepang')->nullable();
            $table->string('ipk')->nullable();
            $table->string('shift_kursus')->nullable();
            $table->string('jurusan_ipk')->nullable();
            $table->text('pengalaman')->nullable();
            $table->text('motivasi')->nullable();
        });
    }
};
