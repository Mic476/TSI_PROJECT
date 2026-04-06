<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlPeriodicDetail extends Model
{
    protected $table = 'pl_periodic_detail';

    protected $fillable = [
        'header_id',
        'periodic_id',
        'area_id',
        'worker_id',
        'periode',
        'cycle',
        'is_active',
        'user_create',
        'user_update',
    ];
}
