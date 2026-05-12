<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('program_schemas')) {
            Schema::create('program_schemas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
                $table->foreignId('batch_id')->nullable()->constrained('batches')->onDelete('set null');
                $table->json('nama_skema');
                $table->string('slug');
                $table->enum('tipe', ['beasiswa', 'scholar_partnership', 'reguler'])->default('reguler');
                $table->json('deskripsi')->nullable();
                $table->json('persyaratan')->nullable();
                $table->decimal('harga', 15, 2)->default(0);
                $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->unique(['program_id', 'slug']);
                
                $table->index('status');
                $table->index('tipe');
                $table->index('sort_order');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('program_schemas');
    }
};
