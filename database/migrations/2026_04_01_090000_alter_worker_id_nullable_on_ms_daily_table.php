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
        Schema::table('ms_daily', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
        });

        Schema::table('ms_daily', function (Blueprint $table) {
            $table->unsignedBigInteger('worker_id')->nullable()->change();
            $table->foreign('worker_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ms_daily', function (Blueprint $table) {
            $table->dropForeign(['worker_id']);
        });

        Schema::table('ms_daily', function (Blueprint $table) {
            $table->unsignedBigInteger('worker_id')->nullable(false)->change();
            $table->foreign('worker_id')->references('id')->on('users');
        });
    }
};
