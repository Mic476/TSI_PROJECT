<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RpDailyLog extends Model
{
    protected $table = 'rp_daily_log';

    protected $fillable = [
        'daily_task_id',
        'work_date',
        'job_status',
        'is_active',
        'user_create',
        'user_update',
    ];
}
