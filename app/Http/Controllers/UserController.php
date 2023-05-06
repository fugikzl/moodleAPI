<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use App\Services\Moodle\MoodleFunctions;
use App\Models\MoodleTokenInfo;
use App\Models\UserCourseModule;
use App\Repositories\UserCourseModuleRepository;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private UserCourseModuleRepository $userCourseModuleRepository;

    public function __construct(UserCourseModuleRepository $userCourseModuleRepository)
    {
        $this->userCourseModuleRepository = $userCourseModuleRepository;    
    }

    public function getUserInfo(string $wstoken)
    {
        $data = MoodleFunctions::getUserInfo($wstoken);

        MoodleTokenInfo::updateOrCreate([
            "ws_token" => $wstoken,
            "user_id" => $data["user_id"]
        ]);

        return response()->json(
            $data    
        );
    }

    public function updateCourseModules(string $wstoken, int $course_id)
    {
        $st = microtime(true);
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);
        $course = Course::where("course_id", $course_id)->first();

        if($course === null){
            $course = Course::create([
                "course_id" => $course_id,
                "name" => MoodleFunctions::getCourseById($wstoken, $course_id)[0]["displayname"]
            ]);
        }

        $res = $this->userCourseModuleRepository->updateCourseModules($wstoken, $course->course_id, $user_id, $course->name);
        $et = microtime(true);
        return [$res, ($et-$st)];
    }

    // public function updateCoursesModules(string $wstoken)
    // {
    //     $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);
    //     $courses = MoodleFunctions::getUserCourses($wstoken, $user_id);

    //     $relativeCourses = array_filter($courses, function ($course) {
    //         $currentDate = time();
    //         return $course['enddate'] > $currentDate;
    //     });

    //     $courses = [];

    //     foreach($relativeCourses as $course){$courses[] = $course;}

    //     $res = $this->userCourseModuleRepository->updateCoursesModules($wstoken, $courses, $user_id);


    //     dd($courses);
    // }
}
