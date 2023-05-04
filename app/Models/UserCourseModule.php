<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCourseModule extends Model
{
    protected $fillable = [
        'cmid',
        'course_id',
        'user_id',
        'grade',
        "name"
    ];

    public $incrementing = false;
    public $timestamps = false;

    public function assignment() : BelongsTo
    {
        return $this->belongsTo(CourseModule::class, "cmid", "cmid");
    }

    public function course() : BelongsTo
    {
        return $this->belongsTo(Course::class, "course_id", "course_id");
    }
}

// $table->id();
// $table->unsignedBigInteger("assignment_id");
// $table->unsignedBigInteger("user_id");
// $table->unsignedFloat("grade");

// $table->foreign("assignment_id")->references("assignment_id")->on("course_assignments");
// $table->foreign("user_id")->references("user_id")->on("moodle_token_infos");