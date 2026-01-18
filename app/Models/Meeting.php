<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'open_date',
        'name',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
