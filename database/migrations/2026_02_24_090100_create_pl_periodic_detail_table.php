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
        Schema::create('pl_periodic_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('header_id');
            $table->unsignedBigInteger('periodic_id');
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('worker_id')->nullable();
            $table->enum('periode', ['mingguan', 'bulanan', 'tahunan']);
            $table->unsignedInteger('cycle');
            $table->date('start_plan_date')->nullable();
            $table->date('generated_until')->nullable();
            $table->string('is_active', 1)->default('1');
            $table->timestamps();
            $table->string('user_create', 50)->nullable();
            $table->string('user_update', 50)->nullable();

            $table->foreign('header_id')->references('id')->on('pl_periodic_header');
            $table->foreign('periodic_id')->references('id')->on('ms_periodic');
            $table->foreign('area_id')->references('id')->on('ms_area');
            $table->foreign('worker_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pl_periodic_detail');
    }
};
