<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlDocumentation extends Model
{
    protected $table = 'pl_documentation';

    protected $fillable = [
        'non_periodic_id',
        'periodic_item_id',
        'file',
        'description',
        'is_active',
        'user_create',
        'user_update',
    ];
}
