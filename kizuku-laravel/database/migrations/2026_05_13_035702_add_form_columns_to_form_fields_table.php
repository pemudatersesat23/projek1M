<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->foreignId('form_id')->nullable()->after('id')->constrained('forms')->onDelete('cascade');
            $table->string('field_role')->default('none')->after('type');
            $table->boolean('is_locked')->default(false)->after('is_required');
            $table->json('settings')->nullable()->after('is_locked');

            $table->index('form_id');
            $table->index('field_role');
        });
    }

    public function down(): void
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropForeign(['form_id']);
            $table->dropIndex(['form_id']);
            $table->dropIndex(['field_role']);
            $table->dropColumn(['form_id', 'field_role', 'is_locked', 'settings']);
        });
    }
};
