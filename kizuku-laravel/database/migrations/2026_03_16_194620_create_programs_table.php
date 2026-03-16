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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('target_peserta')->nullable();
            $table->string('durasi')->nullable();
            $table->text('benefit')->nullable();
            $table->text('alur_seleksi')->nullable();
            $table->string('biaya')->nullable();
            $table->json('faq')->nullable();
            $table->string('materi')->nullable();
            $table->string('brosur')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
