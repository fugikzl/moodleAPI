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
        return [
            "fullname" => $res['fullname'],
            "username" => $res['username'],
            "userid" => $res["userid"],
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

    public static function getCourseAssignments(string $ws_token, int $courseid)
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
}

