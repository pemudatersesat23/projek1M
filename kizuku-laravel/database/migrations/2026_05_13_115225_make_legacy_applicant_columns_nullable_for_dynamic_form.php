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
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('nama')->nullable()->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->text('alamat')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('pendidikan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->string('nama')->nullable(false)->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable(false)->change();
            $table->string('tempat_lahir')->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('pendidikan')->nullable(false)->change();
        });
    }
};
