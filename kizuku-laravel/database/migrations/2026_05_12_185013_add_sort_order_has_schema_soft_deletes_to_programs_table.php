<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            if (!Schema::hasColumn('programs', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_featured');
            }
            if (!Schema::hasColumn('programs', 'has_schema')) {
                $table->boolean('has_schema')->default(false)->after('sort_order');
            }
            if (!Schema::hasColumn('programs', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            if (Schema::hasColumn('programs', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
            if (Schema::hasColumn('programs', 'has_schema')) {
                $table->dropColumn('has_schema');
            }
            if (Schema::hasColumn('programs', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
