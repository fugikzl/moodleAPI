<?php

namespace App\Jobs;

use App\Models\UserCourseModule;
use App\Services\Moodle\MoodleFunctions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetCourseUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $wstoken, 
        private int $user_id, 
        private int $course_id,
        private string $course_name
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // echo("started...\n");
        $wstoken = $this->wstoken;
        $user_id = $this->user_id;
        $course_name = $this->course_name;
        $course_id = $this->course_id;
        
        GetCourseUpdate::dispatch($wstoken,$user_id, $course_id, $course_name)->delay(now()->addSeconds(2));

        $currentUserCourseModules = [];
        $grades = MoodleFunctions::getCourseGrades($wstoken, $course_id, $user_id);
        $userCourseModules = UserCourseModule::where("user_id",$user_id)->where("course_id",$course_id)->get()->keyBy('cmid')->toArray();
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

        if($res !== []){
            print_r($res);
        }
    }

    
}
