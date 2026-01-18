<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    protected $fillable = [
        'folder_id',
        'type',
        'name',
        'url',
        'order_by',
    ];
}
