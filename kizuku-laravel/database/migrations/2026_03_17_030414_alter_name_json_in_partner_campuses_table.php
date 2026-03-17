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
        // Clear existing non-json data to prevent cast error
        \Illuminate\Support\Facades\DB::table('partner_campuses')->truncate();
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE partner_campuses MODIFY name JSON');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE partner_campuses MODIFY name VARCHAR(255)');
    }
};
