<?php

use App\Http\Controllers\MoodleApiController;
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

Route::get("/{wstoken}/get-user-info",[MoodleApiController::class,"getUserInfo"]); 
Route::get("/{wstoken}/get-user-courses",[MoodleApiController::class,"getUserCourses"]);
Route::get("/{wstoken}/get-user-relative-courses",[MoodleApiController::class,"getUserRelativeCourses"]);
Route::get("/{wstoken}/course/{course_id}/get-grade",[MoodleApiController::class,"getCourseGrades"]);
Route::get("/{wstoken}/course/{course_id}/get-contents",[MoodleApiController::class,"getCourseContents"]);
Route::get("/{wstoken}/course/{course_id}/get-assignments",[MoodleApiController::class,"getCourseAssignments"]);
Route::get("/{wstoken}/course/get-assignments",[MoodleApiController::class,"getCoursesAssignments"]);







