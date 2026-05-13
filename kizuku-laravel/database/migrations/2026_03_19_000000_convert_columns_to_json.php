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
        $tables = [
            'beritas' => ['judul', 'isi'],
            'fasilitas' => ['nama'],
            'programs' => ['nama_program', 'deskripsi', 'focus', 'output', 'target_peserta', 'benefit', 'alur_seleksi', 'materi', 'durasi', 'biaya'],
            'testimonials' => ['name', 'role', 'content'],
            'hero_sections' => ['title', 'subtitle', 'badge_text'],
        ];

        foreach ($tables as $table => $columns) {
            if (!Schema::hasTable($table)) continue;

            foreach ($columns as $column) {
                if (!Schema::hasColumn($table, $column)) continue;

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

                // 2. Modify column to JSON type on databases that support MySQL's MODIFY syntax.
                if (DB::connection()->getDriverName() !== 'sqlite') {
                    DB::statement("ALTER TABLE {$table} MODIFY {$column} JSON");
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not implemented
    }
};
