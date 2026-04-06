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
        Schema::create('pl_documentation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('non_periodic_id')->nullable();
            $table->unsignedBigInteger('periodic_item_id')->nullable();
            $table->string('file');
            $table->text('description')->nullable();
            $table->string('is_active', 1)->default('1');
            $table->timestamps();
            $table->string('user_create', 50)->nullable();
            $table->string('user_update', 50)->nullable();

            $table->foreign('non_periodic_id')->references('id')->on('pl_non_periodic')->onDelete('cascade');
            $table->foreign('periodic_item_id')->references('id')->on('pl_periodic_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pl_documentation');
    }
};
