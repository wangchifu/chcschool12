<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    protected $fillable = [
        'semester',
        'student_year',
        'student_class',
        'class_name',
        'user_ids',
        'user_names',
    ];
}
