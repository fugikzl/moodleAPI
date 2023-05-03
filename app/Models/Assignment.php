<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * That model represents Assignment in moodle
 * 
 * @property $assignment_id int primary key, id of assignment
 * @property $course_id int id of course
 * @property $name string name of assignment
 * 
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo course()
 */
class Assignment extends Model
{
    protected $primaryKey = 'assignment_id';

    private $fillable = [
        'assignment_id',
        'course_id',
        'name'
    ];

    public $timestamps = false;

    public function course() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }
}
