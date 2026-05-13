<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->foreignId('schema_id')->nullable()->constrained('program_schemas')->onDelete('cascade');
            $table->foreignId('batch_id')->nullable()->constrained('batches')->onDelete('set null');
            
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('success_message')->nullable();
            
            $table->string('status')->default('draft');
            $table->boolean('is_active')->default(true);
            $table->boolean('accepts_responses')->default(true);
            $table->integer('version')->default(1);
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('program_id');
            $table->index('schema_id');
            $table->index('batch_id');
            $table->index('status');
            $table->index('is_active');
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
