<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update enum for status
        DB::statement("ALTER TABLE batches MODIFY COLUMN status ENUM('akan_dibuka', 'dibuka', 'diperpanjang', 'ditutup', 'berjalan', 'selesai') NOT NULL DEFAULT 'akan_dibuka'");

        Schema::table('batches', function (Blueprint $table) {
            if (!Schema::hasColumn('batches', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            if (Schema::hasColumn('batches', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
        
        // Revert enum (may fail if there are 'diperpanjang' records, but it's okay for down)
        DB::statement("ALTER TABLE batches MODIFY COLUMN status ENUM('akan_dibuka', 'dibuka', 'ditutup', 'berjalan', 'selesai') NOT NULL DEFAULT 'akan_dibuka'");
    }
};
