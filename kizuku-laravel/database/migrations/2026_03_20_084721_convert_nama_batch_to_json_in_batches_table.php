<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $table = 'batches';
        $column = 'nama_batch';

        if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
            // 1. Convert existing data to JSON in PHP
            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                $value = $row->$column;
                // If it's not null and not already JSON, convert it
                if ($value !== null && !str_starts_with(trim($value), '{')) {
                    DB::table($table)->where('id', $row->id)->update([
                        $column => json_encode(['id' => $value])
                    ]);
                }
            }

            // 2. Modify column to JSON type
            // On Windows/MariaDB/MySQL, we use MODIFY.
            DB::statement("ALTER TABLE {$table} MODIFY {$column} JSON");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            //
        });
    }
};
