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
        Schema::create('pl_periodic_header', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('tahun');
            $table->string('keterangan', 255)->nullable();
            $table->string('is_active', 1)->default('1');
            $table->timestamps();
            $table->string('user_create', 50)->nullable();
            $table->string('user_update', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pl_periodic_header');
    }
};
