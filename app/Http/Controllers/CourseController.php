<?php

namespace App\Http\Controllers;

use App\Models\MoodleTokenInfo;
use App\Services\Moodle\MoodleFunctions;
use App\Services\Moodle\Resources\CourseContentResource;
use Illuminate\Http\Request;
use App\Services\Moodle\Resources\MoodleCourseResource;

class CourseController extends Controller
{
    public function getCourseGrades(string $wstoken, int $course_id)
    {
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);

        return response()->json(
            MoodleFunctions::getCourseGrades($wstoken,$course_id,$user_id)
        );    
    }

    public function getUserCourses(string $wstoken)
    {
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);
        $courses = MoodleFunctions::getUserCourses($wstoken, $user_id);


        return response()->json(
            MoodleCourseResource::collection(collect($courses))
        );
    }

    public function getUserRelativeCourses(string $wstoken)
    {
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);
        $courses = MoodleFunctions::getUserCourses($wstoken, $user_id);

        $relativeCourses = array_filter($courses, function ($course) {
            $currentDate = time();
            return $course['enddate'] > $currentDate;
        });

        return response()->json(
            MoodleCourseResource::collection(collect($relativeCourses))
        );    
    
    }

    public function getCourseContents(string $wstoken, int $course_id)
    {
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);

        return response()->json(
            CourseContentResource::collection(collect(MoodleFunctions::getCourseContents($wstoken,$course_id,$user_id)))
        );    
    }

    public function getCourseByid(string $wstoken, int $course_id)
    {
        return response()->json(
            MoodleFunctions::getCourseByid($wstoken,$course_id)[0]
        );
    }
}
