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
        Schema::create('ms_periodic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('job_description');
            $table->string('is_active', 1)->default('1');
            $table->string('user_create', 50)->nullable();
            $table->string('user_update', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_periodic');
    }
};
