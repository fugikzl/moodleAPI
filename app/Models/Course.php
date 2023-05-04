<?php

namespace App\Models;

use App\Services\Moodle\MoodleFunctions;
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
    public $incrementing = false;

    protected $primaryKey = "course_id";

    protected $fillable = [
        'name',
        'course_id'
    ];

    public static function isCourseStores(int $course_id) : bool
    {
        return self::where("course_id",$course_id)->count() > 0 ? true : false;
    }

    public static function getOrStoreCourse(int $course_id, string $ws_token) : Course
    {
        if(self::isCourseStores($course_id)){
            return Course::where("course_id",$course_id)->first();
        }else{
            $data = MoodleFunctions::getCourseById($ws_token, $course_id);
            $course = Course::updateOrCreate([
                "name" => $data["name"],
                "course_id" => $data["courseid"]
            ]);
            return $course;
        }
    }

}
