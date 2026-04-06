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
        Schema::create('rp_daily_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('daily_task_id');
            $table->date('work_date');
            $table->string('job_status', 20)->default('pending');
            $table->string('is_active', 1)->default('1');
            $table->timestamps();
            $table->string('user_create', 50)->nullable();
            $table->string('user_update', 50)->nullable();

            $table->foreign('daily_task_id')->references('id')->on('ms_daily');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rp_daily_log');
    }
};
