<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\MoodleTokenInfo;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Models\UserCourseModule;
use App\Services\Moodle\MoodleFunctions;

/**
 * Class UserCourseModuleRepository.
 */
class UserCourseModuleRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return UserCourseModule::class;
    }

    public function updateCourseModules(string $wstoken, int $course_id, int $user_id, string $course_name) : array
    {
        $currentUserCourseModules = [];
        $grades = MoodleFunctions::getCourseGrades($wstoken, $course_id, $user_id);
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

        $userCourseModules = UserCourseModule::where("user_id",$user_id)->where("course_id",$course_id)->get()->keyBy('cmid')->toArray();
        $res = [];
        foreach($currentUserCourseModules as $currentUserCourseModule){
            if(array_key_exists($currentUserCourseModule["cmid"], $userCourseModules)){
                if($currentUserCourseModule["grade"] !== $userCourseModules[$currentUserCourseModule["cmid"]]["grade"]){
                    $res[] = [
                        "course_name" => $course_name,
                        "module_name" => $currentUserCourseModule["name"],
                        "old_grade" => $userCourseModules[$currentUserCourseModule["cmid"]]["grade"],
                        "current_grade" => $currentUserCourseModule["grade"]
                    ];
                }
            }else{
                $res[] = [
                    "course_name" => $course_name,
                    "module_name" => $currentUserCourseModule["name"],
                    "old_grade" => -1,
                    "current_grade" => $currentUserCourseModule["grade"]
                ];
            }
        } 
        return $res;
    }
}
