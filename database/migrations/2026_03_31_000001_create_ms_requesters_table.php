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
        if (Schema::hasTable('ms_requesters')) {
            return;
        }

        Schema::create('ms_requesters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('requester_name', 100)->unique();
            $table->string('is_active', 1)->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('ms_requesters')) {
            Schema::dropIfExists('ms_requesters');
        }
    }
};
