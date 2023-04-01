<?php

namespace App\Http\Controllers;

use App\Http\Resources\MoodleCourseResource;
use App\Models\MoodleTokenInfo;
use App\Services\Moodle\MoodleFunctions;
use Illuminate\Http\Request;

class MoodleApiController extends Controller
{
    public function getUserInfo(string $wstoken)
    {
        return response()->json(
            MoodleFunctions::getUserInfo($wstoken)
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

    public function getUserCourseGrade(string $wstoken, int $course_id)
    {
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);

        return response()->json(
            MoodleFunctions::getCourseGrades($wstoken,$course_id,$user_id)
        );    
    }
}
