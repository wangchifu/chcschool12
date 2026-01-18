<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixClass extends Model
{
    protected $fillable = [
        'name',
        'order_by',
        'disable',
    ];
}
