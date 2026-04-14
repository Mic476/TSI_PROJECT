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
        if (Schema::hasTable('pu2_requests') && !Schema::hasTable('pl_non_periodic')) {
            Schema::rename('pu2_requests', 'pl_non_periodic');
            if (!Schema::hasColumn('pl_non_periodic', 'requester_name')) {
                Schema::table('pl_non_periodic', function (Blueprint $table) {
                    $table->string('requester_name', 100)->nullable()->after('area_id');
                });
            }
            return;
        }

        if (Schema::hasTable('pl_non_periodic')) {
            if (!Schema::hasColumn('pl_non_periodic', 'requester_name')) {
                Schema::table('pl_non_periodic', function (Blueprint $table) {
                    $table->string('requester_name', 100)->nullable()->after('area_id');
                });
            }
            return;
        }

        Schema::create('pl_non_periodic', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('area_id', 50)->nullable();
            $table->string('requester_name', 100)->nullable();
            $table->string('job_description')->nullable();
            $table->string('work_type', 20)->nullable();
            $table->string('vendor_name', 100)->nullable();
            $table->unsignedBigInteger('worker_id')->nullable();
            $table->date('realization_date')->nullable();
            $table->string('request_status', 50)->default('pending');
            $table->unsignedBigInteger('head_approver_id')->nullable();
            $table->text('head_note')->nullable();
            $table->date('head_approval_date')->nullable();
            $table->unsignedBigInteger('hrd_approval_id')->nullable();
            $table->text('hrd_note')->nullable();
            $table->date('hrd_approval_date')->nullable();
            $table->text('requester_note')->nullable();
            $table->text('revision_attachment')->nullable();
            $table->dateTime('pengadaan_started_at')->nullable();
            $table->dateTime('pengadaan_ended_at')->nullable();
            $table->dateTime('pengerjaan_started_at')->nullable();
            $table->dateTime('pengerjaan_ended_at')->nullable();
            $table->dateTime('petugas_confirmed_at')->nullable();
            $table->text('attachment')->nullable();
            $table->string('is_active', 1)->default('1');
            $table->timestamps();
            $table->string('user_create', 50)->nullable();
            $table->string('user_update', 50)->nullable();

            $table->foreign('worker_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pl_non_periodic')) {
            Schema::dropIfExists('pl_non_periodic');
        }
    }
};
