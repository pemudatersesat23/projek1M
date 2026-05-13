<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->foreignId('form_id')->nullable()->after('schema_id')->constrained('forms')->onDelete('set null');
            $table->integer('form_version_snapshot')->nullable()->after('form_id');
            $table->json('form_title_snapshot')->nullable()->after('form_version_snapshot');
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropForeign(['form_id']);
            $table->dropColumn(['form_id', 'form_version_snapshot', 'form_title_snapshot']);
        });
    }
};
