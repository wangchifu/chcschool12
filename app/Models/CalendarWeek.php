<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarWeek extends Model
{
    protected $fillable = [
        'semester',
        'week',
        'start_end',
    ];
}
