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
        Schema::table('beritas', function (Blueprint $table) {
            $table->string('image')->nullable()->after('kategori');
            $table->string('lokasi')->nullable()->after('image');
            $table->dropColumn('emoji');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->string('emoji')->default('📢')->after('kategori');
            $table->dropColumn(['image', 'lokasi']);
        });
    }
};
