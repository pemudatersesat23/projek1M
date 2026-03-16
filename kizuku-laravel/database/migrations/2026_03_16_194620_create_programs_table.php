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
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('explanation')->nullable();
            $table->text('target_participants')->nullable();
            $table->string('duration')->nullable();
            $table->text('benefits')->nullable();
            $table->text('selection_flow')->nullable();
            $table->string('cost')->nullable();
            $table->json('faq')->nullable();
            $table->text('materi')->nullable();
            $table->string('brosur_path')->nullable();
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
