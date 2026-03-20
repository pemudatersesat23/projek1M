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
        Schema::table('applicant_documents', function (Blueprint $table) {
            $table->string('cv')->nullable()->after('sertifikat');
            $table->string('transkrip')->nullable()->after('cv');
            $table->string('bukti_sosmed')->nullable()->after('transkrip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicant_documents', function (Blueprint $table) {
            $table->dropColumn(['cv', 'transkrip', 'bukti_sosmed']);
        });
    }
};
