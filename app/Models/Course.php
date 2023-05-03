<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * That model represents course in moodle
 * 
 * @property $course_id int primary key, id of course
 * @property $name string name of course 
 */
class Course extends Model
{
    public $timestamps = false;

    protected $primaryKey = "course_id";

    protected $fillable = [
        'name',
        'course_id'
    ];

}
