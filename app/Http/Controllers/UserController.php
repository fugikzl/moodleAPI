<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use App\Services\Moodle\MoodleFunctions;
use App\Models\MoodleTokenInfo;
use App\Models\UserCourseModule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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

    public function updateCourseModules(string $wstoken, int $course_id){
        $st = microtime(true);
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);
        $course = Course::where("course_id", $course_id)->first();

        if($course === null){
            $course = Course::create([
                "course_id" => $course_id,
                "name" => MoodleFunctions::getCourseById($wstoken, $course_id)[0]["displayname"]
            ]);
        }
        $grades = MoodleFunctions::getCourseGrades($wstoken, $course_id, $user_id);

        $userCourseModules = UserCourseModule::where("user_id",$user_id)->where("course_id",$course_id)->get()->keyBy('cmid')->toArray();
        $currentUserCourseModules = [];

        foreach($grades as $grade){
            $tempModule = UserCourseModule::where([
                "cmid" => $grade["cmid"],
                "user_id" => $user_id,
                "course_id" => $course_id
            ])->first();

            if($tempModule){
                $tempModule->grade = floatval(explode(" ", $grade["percentageformatted"])[0]);
                $tempModule->save();
                $currentUserCourseModules[$tempModule->cmid] = $tempModule;
            }else{
                $currentUserCourseModules[$grade["cmid"]] = UserCourseModule::create([
                    "grade" => floatval(explode(" ", $grade["percentageformatted"])[0]),
                    "cmid" => $grade["cmid"],
                    "course_id" => $course_id,
                    "name" => $grade["name"],
                    "user_id" => $user_id,
                ]);
            }
        }

        $res = [];
        foreach($currentUserCourseModules as $currentUserCourseModule){
            if(array_key_exists($currentUserCourseModule["cmid"], $userCourseModules)){
                if($currentUserCourseModule["grade"] !== $userCourseModules[$currentUserCourseModule["cmid"]]["grade"]){
                    $res[] = [
                        "course_name" => $course->name,
                        "module_name" => $currentUserCourseModule["name"],
                        "old_grade" => $userCourseModules[$currentUserCourseModule["cmid"]]["grade"],
                        "current_grade" => $currentUserCourseModule["grade"]
                    ];
                }
            }else{
                $res[] = [
                    "course_name" => $course->name,
                    "module_name" => $currentUserCourseModule["name"],
                    "old_grade" => -1,
                    "current_grade" => $currentUserCourseModule["grade"]
                ];
            }
        } 
        $et = microtime(true);
        return [$res, ($et-$st)];
    }
}
