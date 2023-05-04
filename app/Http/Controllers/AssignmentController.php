<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Moodle\MoodleFunctions;
use App\Models\MoodleTokenInfo;

class AssignmentController extends Controller
{
    public function getCourseAssignments(string $wstoken, int $course_id)
    {
        $user_id = MoodleTokenInfo::getOrStoreUser($wstoken);

        return response()->json(
            MoodleFunctions::getAssignmentsByCourse($wstoken, $course_id)
        );
    }
}
