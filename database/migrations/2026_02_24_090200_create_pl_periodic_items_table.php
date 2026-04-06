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
        Schema::create('pl_periodic_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_id');
            $table->date('planned_date');
            $table->date('realization_date')->nullable();
            $table->char('is_active', 1)->default('1');
            $table->timestamps();
            $table->string('user_create', 50)->nullable();
            $table->string('user_update', 50)->nullable();

            $table->foreign('detail_id')->references('id')->on('pl_periodic_detail')->onDelete('cascade');
            $table->unique(['detail_id', 'planned_date'], 'uniq_periodic_detail_plan_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pl_periodic_items');
    }
};
