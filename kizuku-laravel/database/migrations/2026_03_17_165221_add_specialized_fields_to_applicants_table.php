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
            // Fisik (Untuk Magang/TG)
            $table->integer('tinggi_badan')->nullable()->after('pengalaman_kerja');
            $table->integer('berat_badan')->nullable()->after('tinggi_badan');
            $table->string('kondisi_mata')->nullable()->after('berat_badan');
            $table->boolean('tato')->default(false)->after('kondisi_mata');
            $table->boolean('merokok')->default(false)->after('tato');
            
            // Skill & Pendidikan (Untuk Engineer/TG)
            $table->string('bidang_ssw')->nullable()->after('merokok');
            $table->string('level_bahasa_jepang')->nullable()->after('bidang_ssw');
            $table->string('ipk')->nullable()->after('level_bahasa_jepang'); // Structured IPK
            $table->string('shift_kursus')->nullable()->after('ipk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'tinggi_badan', 'berat_badan', 'kondisi_mata', 
                'tato', 'merokok', 'bidang_ssw', 
                'level_bahasa_jepang', 'ipk', 'shift_kursus'
            ]);
        });
    }
};
