<?php

namespace App\Services\Moodle;

use Exception;

/**
 * @method array getUserInfo()
 * @method array getUserCourses()
 * @method array getCoursesInfo()
 * @method array getUserCoursesGrade()
 * @method array getCourseGrades()
 * @method array getCourseInfoTable()
 * @method array getCourseContents()
 * @method array getCourseById()
 * @method array getAssignmentsByCourse()
 */
class MoodleFunctions
{
    /**
     * @param string $ws_token 
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

    /**
     * @param string $wstoken
     * @param int $userid
     */
    public static function getUserCourses(string $ws_token, int $userid)
    {
        $moodleRequest = new MoodleRequest($ws_token,"core_enrol_get_users_courses",["userid" => $userid]);
        return $moodleRequest->send();
    }

    /**
     * @param int $courseid
     * @param string $wstoken
     */
    public static function getCoursesInfo(string $ws_token, int $courseid)
    {
        $moodleRequest = new MoodleRequest($ws_token,"core_course_get_contents",["courseid" => $courseid]);
        return $moodleRequest->send();
    }

    // public static function generateUrl(string $ws_token, $url)
    // {
    //     // return config("moodle.webservice_url").$url.""
    // }

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

        // dd($usergrades["usergrades"][0]['gradeitems']);
        $gradeitems = [];
        foreach($usergrades["usergrades"][0]['gradeitems'] as $gradeitem)
        {
            if(($gradeitem["gradeformatted"] != "-" or $gradeitem["percentageformatted"] != "-") && array_key_exists("cmid",$gradeitem))
            {
                $gradeitems[] = [
                    "id" => $gradeitem["id"],
                    "name" => $gradeitem["itemname"],
                    "percentageformatted" => $gradeitem["percentageformatted"],
                    "feedback" => $gradeitem["feedback"],
                    "iteminstance" => $gradeitem["iteminstance"],
                    "cmid" => $gradeitem["cmid"] ,
                    "course_id" => $courseid
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
        
        return $moodleRequest->send()["courses"] === [] 
            ? throw new Exception("No such a course.", 404) 
            : $moodleRequest->send()["courses"];
    }

    public static function getAssignmentsByCourse(string $ws_token, int $courseid)
    {
        $moodleRequest = new MoodleRequest($ws_token,"mod_assign_get_assignments",[
            "courseids[0]" => $courseid,
        ]);

        return $moodleRequest->send();
    }

    public static function getAssignmentsByCourses(string $ws_token, array $courseIds)
    {
        $param = [];
        foreach($courseIds as $i=>$id)
        {
            $param["courseids[$i]"] = $id;
        }
        $moodleRequest = new MoodleRequest($ws_token,"mod_assign_get_assignments",$param);

        return $moodleRequest->send();
    }
}

