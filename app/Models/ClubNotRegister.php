<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubNotRegister extends Model
{
    protected $fillable = [
        'semester',
        'ip',
        'event',
    ];
}
