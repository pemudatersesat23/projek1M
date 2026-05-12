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
        // 1. form_fields
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->restrictOnDelete();
            $table->foreignId('schema_id')->nullable()->constrained('program_schemas')->restrictOnDelete();
            $table->json('label');
            $table->string('field_name');
            $table->string('type');
            $table->json('placeholder')->nullable();
            $table->json('description')->nullable();
            $table->json('options')->nullable();
            $table->json('accepted_file_types')->nullable();
            $table->integer('max_file_size')->nullable();
            $table->boolean('is_required')->default(false);
            $table->string('status')->default('aktif');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('program_id');
            $table->index('schema_id');
            $table->index('type');
            $table->index('status');
            $table->index('sort_order');
            $table->index('field_name');
        });

        // 2. applicant_form_answers
        Schema::create('applicant_form_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->cascadeOnDelete();
            $table->foreignId('form_field_id')->constrained('form_fields')->restrictOnDelete();
            $table->json('value')->nullable();
            $table->json('field_label_snapshot')->nullable();
            $table->string('field_type_snapshot')->nullable();
            $table->timestamps();

            $table->index('applicant_id');
            $table->index('form_field_id');
        });

        // 3. applicant_dynamic_files
        Schema::create('applicant_dynamic_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->cascadeOnDelete();
            $table->foreignId('form_field_id')->constrained('form_fields')->restrictOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->integer('size');
            $table->json('field_label_snapshot')->nullable();
            $table->string('field_type_snapshot')->nullable();
            $table->timestamps();

            $table->index('applicant_id');
            $table->index('form_field_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_dynamic_files');
        Schema::dropIfExists('applicant_form_answers');
        Schema::dropIfExists('form_fields');
    }
};
