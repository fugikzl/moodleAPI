<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseContentResource;
use App\Http\Resources\MoodleCourseResource;
use App\Models\MoodleTokenInfo;
use App\Services\Moodle\MoodleFunctions;
use Illuminate\Http\Request;

class MoodleApiController extends Controller
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;    
    }

    public function getUserInfo(string $wstoken)
    {
        $data = MoodleFunctions::getUserInfo($wstoken);

        MoodleTokenInfo::create([
            "ws_token" => $wstoken,
            "user_id" => $data["user_id"]
        ]);

        return response()->json(
            $data    
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

    public function getCourseGrades(string $wstoken, int $course_id)
    {
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);

        return response()->json(
            MoodleFunctions::getCourseGrades($wstoken,$course_id,$user_id)
        );    
    }

    public function getCourseContents(string $wstoken, int $course_id)
    {
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);

        return response()->json(
            CourseContentResource::collection(collect(MoodleFunctions::getCourseContents($wstoken,$course_id,$user_id)))
        );    
    }

    public function getCourseAssignments(string $wstoken, int $course_id)
    {
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);

        return response()->json(
            MoodleFunctions::getAssignmentsByCourse($wstoken, $course_id)
        );
    }

    public function getCoursesAssignments(string $wstoken)
    {

    }
}
