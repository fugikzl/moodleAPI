<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\MoodleTokenInfo;
use App\Services\Moodle\MoodleFunctions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartUserCourseUpdates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $wstoken
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $wstoken = $this->wstoken;
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);
        $courses = MoodleFunctions::getUserCourses($wstoken, $user_id);

        $relativeCourses = array_filter($courses, function ($course) {
            $currentDate = time();
            return $course['enddate'] > $currentDate;
        });

        foreach($relativeCourses as $relativeCourse)
        {
            Course::firstOrCreate([
                "course_id" => $relativeCourse["id"],
                "name" => $relativeCourse["displayname"]
            ]);
        }

        foreach($relativeCourses as $relativeCourse)
        {
            GetCourseUpdate::dispatch($wstoken, $user_id, $relativeCourse["id"],$relativeCourse['shortname']);
            // GetCourseUpdate::dispatch($wstoken,$user_id, $course_id, $course_name)->delay(now()->addSeconds(2));
        }


    }
}
