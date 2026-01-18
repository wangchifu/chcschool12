<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'title',
        'group_id',
        'content',
        'order_by',
        'views',
    ];
}
