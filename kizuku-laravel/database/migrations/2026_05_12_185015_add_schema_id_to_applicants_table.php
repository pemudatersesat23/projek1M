<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            if (!Schema::hasColumn('applicants', 'schema_id')) {
                $table->foreignId('schema_id')->nullable()->after('program_id')->constrained('program_schemas')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            if (Schema::hasColumn('applicants', 'schema_id')) {
                $table->dropForeign(['schema_id']);
                $table->dropColumn('schema_id');
            }
        });
    }
};
