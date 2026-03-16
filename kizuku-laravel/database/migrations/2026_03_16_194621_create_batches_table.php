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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('status', ['akan_dibuka', 'dibuka', 'ditutup', 'berjalan', 'selesai'])->default('akan_dibuka');
            $table->date('registration_start')->nullable();
            $table->date('registration_end')->nullable();
            $table->date('class_start')->nullable();
            $table->date('class_estimate_end')->nullable();
            $table->integer('quota')->nullable();
            $table->string('link_form')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
