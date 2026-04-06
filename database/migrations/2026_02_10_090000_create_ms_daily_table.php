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
        Schema::create('ms_daily', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('worker_id')->nullable();
            $table->text('job_description');
            $table->string('assigned_role', 50)->nullable()->comment('Role assignment: ptgoff or ptgspu');
            $table->string('is_active', 1)->default('1');
            $table->string('user_create', 50)->nullable();
            $table->string('user_update', 50)->nullable();
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_daily');
    }
};
