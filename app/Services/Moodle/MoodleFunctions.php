<?php

namespace App\Services\Moodle;

class MoodleFunctions
{
    /**
     * 
     * @param string $ws_token 
     * 
     */
    public static function getUserInfo(string $ws_token)
    {
        $moodleRequest = new MoodleRequest($ws_token,"core_webservice_get_site_info");
        $res = $moodleRequest->send();
        return[
            "full_name" => $res['fullname'],
            "username" => $res['username'],
            "user_id" => $res["userid"],
        ];
    }

    public static function getUserCourses(string $ws_token, int $userid)
    {
        $moodleRequest = new MoodleRequest($ws_token,"core_enrol_get_users_courses",["userid" => $userid]);
        return $moodleRequest->send();
    }

    public static function getCoursesInfo(string $ws_token, int $courseid)
    {
        $moodleRequest = new MoodleRequest($ws_token,"core_course_get_contents",["courseid" => $courseid]);
        return $moodleRequest->send();
    }

    public static function generateUrl(string $ws_token, $url)
    {
        // return config("moodle.webservice_url").$url.""
    }

    public static function getUserCoursesGrade(string $ws_token, int $userid)
    {
        $moodleRequest = new MoodleRequest($ws_token,"gradereport_overview_get_course_grades",[
            "userid" => $userid,
        ]);
        return $moodleRequest->send();
    }

    public static function getCourseGrades(string $ws_token, int $courseid, int $userid)
    {

        $moodleRequest = new MoodleRequest($ws_token,"gradereport_user_get_grade_items",[
            "courseid" => $courseid,
            "userid" => $userid,
        ]);
        $usergrades = $moodleRequest->send();

        $gradeitems = [];
        foreach($usergrades["usergrades"][0]['gradeitems'] as $gradeitem)
        {
            if($gradeitem["gradeformatted"] != "-" or $gradeitem["percentageformatted"] != "-")
            {
                $gradeitems[] = [
                    "itemname" =>$gradeitem["itemname"],
                    "percentageformatted" =>$gradeitem["percentageformatted"],
                    "feedback" =>$gradeitem["feedback"],

                ];
            }
        }

        return $gradeitems;
    }

    public static function getCourseInfoTable(string $ws_token, int $courseid, int $userid)
    {

        $moodleRequest = new MoodleRequest($ws_token,"gradereport_user_get_grades_table",[
            "courseid" => $courseid,
            "userid" => $userid,
        ]);
        return $moodleRequest->send();
    }

    public static function getCourseContents(string $ws_token, int $courseid)
    {
        $moodleRequest = new MoodleRequest($ws_token,"core_course_get_contents",[
            // "wsfunction" => "core_course_get_contents",
            "courseid" => $courseid,
            // "moodlerestformat" => "json",
            // "userid" => $userid,
        ]);
        return $moodleRequest->send();
    }

    public static function getCourseById(string $ws_token, int $courseid)
    {
        $moodleRequest = new MoodleRequest($ws_token,"core_course_get_courses_by_field",[
            "field" => "id",
            "value" => $courseid,
        ]);
        return $moodleRequest->send()["courses"];
    }

    public static function getAssignmentsByCourse(string $ws_token, int $courseid)
    {
        $moodleRequest = new MoodleRequest($ws_token,"mod_assign_get_assignments",[
            "courseids[0]" => $courseid,
        ]);

        return $moodleRequest->send();
    }

    // public static function getAssignmentsByCourses(string $ws_token, array $courseIds)
    // {
    //     $moodleRequest = new MoodleRequest($ws_token,"core_course_get_courses_by_field",[
    //         "field" => "id",
    //         "value" => $courseid,
    //     ]);

    //     return $moodleRequest->send();
    // }
}

