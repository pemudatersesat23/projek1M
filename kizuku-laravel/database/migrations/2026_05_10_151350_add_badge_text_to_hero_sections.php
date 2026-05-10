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
        Schema::table('hero_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('hero_sections', 'badge_text')) {
                $table->json('badge_text')->nullable()->after('image_path');
            }
            $table->json('btn_primary_text')->nullable()->change();
            $table->json('btn_secondary_text')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_sections', function (Blueprint $table) {
            $table->dropColumn('badge_text');
            $table->string('btn_primary_text')->change();
            $table->string('btn_secondary_text')->change();
        });
    }
};
