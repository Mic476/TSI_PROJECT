<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlPeriodicHeader extends Model
{
    protected $table = 'pl_periodic_header';

    protected $fillable = [
        'tahun',
        'keterangan',
        'is_active',
        'user_create',
        'user_update',
    ];
}
