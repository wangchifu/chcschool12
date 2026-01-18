<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostType extends Model
{
    protected $fillable = [
        'name',
        'order_by',
        'disable',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
