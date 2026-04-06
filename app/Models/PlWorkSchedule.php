<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlWorkSchedule extends Model
{
    protected $table = 'pl_work_schedule';

    protected $fillable = [
        'request_id',
        'periodic_id',
        'worker_id',
        'plan_date',
        'realization_date',
        'job_status',
        'is_active',
        'user_create',
        'user_update',
    ];
}
