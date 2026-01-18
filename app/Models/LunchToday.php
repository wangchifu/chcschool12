<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LunchToday extends Model
{
    protected $fillable = [
        'school_id',
        'school_name'
    ];
}
