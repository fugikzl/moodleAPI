<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\MoodleApiController;
use App\Http\Controllers\UserController;
use App\Jobs\StartUserCourseUpdates;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get("/",function(){
    return response()->json(status:200);
});

Route::get("/{wstoken}/get-user-info",[UserController::class,"getUserInfo"]); 
Route::get("/{wstoken}/course/{course_id}/updateModules",[UserController::class,"updateCourseModules"]);
Route::get("/{wstoken}/course/update-modules",[UserController::class,"updateCoursesModules"]);

Route::get("/{wstoken}/get-user-courses",[CourseController::class,"getUserCourses"]);
Route::get("/{wstoken}/get-user-relative-courses",[CourseController::class,"getUserRelativeCourses"]);
Route::get("/{wstoken}/course/{course_id}",[CourseController::class, "getCourseById"]);
Route::get("/{wstoken}/course/{course_id}/get-grade",[CourseController::class,"getCourseGrades"]);
Route::get("/{wstoken}/course/{course_id}/get-contents",[CourseController::class,"getCourseContents"]);

Route::get("/{wstoken}/course/{course_id}/get-assignments",[AssignmentController::class,"getCourseAssignments"]);
Route::get("/{wstoken}/course/get-assignments",[AssignmentController::class,"getCoursesAssignments"]);

Route::post("/{wstoken}/registerUpdate",function(string $wstoken){
    StartUserCourseUpdates::dispatch($wstoken);
});
// Route::get("/test",function(){
//     $client = new Client();
//     $res = $client->post(env("TELEGRAM_BOT_API_ENDPOINT")."/set-update",[
//         "body" => json_encode([
//             "name" => "hui"
//         ])
//     ]);

//     return $res->getBody();
// });






