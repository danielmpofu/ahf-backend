<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseProgressController;
use App\Http\Controllers\CourseResourceController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LevelController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'api', 'prefix' => 'exercises'],
    function () {
        Route::get('/', [ExerciseController::class, 'index']);
        Route::post('/', [ExerciseController::class, 'store']);
        Route::get('delete/{exercise}', [ExerciseController::class, 'destroy']);
        Route::get('/{course}', [ExerciseController::class, 'findByCourse']);
    });

Route::group(['middleware' => 'api', 'namespace' => 'Api\Http\Controllers', 'prefix' => 'auth'],
    function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('check_existence', [AuthController::class, 'check_existence']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

Route::group(['middleware' => 'api', 'prefix' => 'courses'],
    function () {
        Route::get('/', [CourseController::class, 'index']);
        Route::post('new', [CourseController::class, 'create']);
        Route::get('my-courses', [CourseController::class, 'myCourses']);
        Route::get('enroll/{course}', [CourseController::class, 'enrollCourse']);
        Route::get('find/{id}', [CourseController::class, 'show']);
        Route::get('delete/{id}', [CourseController::class, 'destroy']);
        Route::post('update/{id}', [CourseController::class, 'update']);
        Route::post('faqs/{id}', [CourseController::class, 'add_faq']);
        Route::get('faqs/{id}', [CourseController::class, 'get_faq']);
    });

Route::group(['middleware' => 'api', 'prefix' => 'resources'],
    function () {
        Route::post('/', [CourseResourceController::class, 'store']);
        Route::get('/{course}', [CourseResourceController::class, 'index']);
        Route::get('find/{resource}', [CourseResourceController::class, 'show']);
        Route::post('view', [CourseResourceController::class, 'addResourceView']);
    });

Route::group(['middleware' => 'api', 'prefix' => 'progress'],
    function () {
        Route::get('check/{course_id}/{resource_id}/{resource_type}', [CourseProgressController::class, 'index']);
        Route::get('overview/', [CourseProgressController::class, 'progress_overview']);
        Route::post('save/', [CourseProgressController::class, 'store']);
    });

Route::group(['middleware' => 'api', 'prefix' => 'comments'],
    function () {
        Route::get('find/{entity_id}/{entity_type}', [CommentController::class, 'index']);
        Route::post('comment/', [CommentController::class, 'store']);
    });


Route::group(['middleware' => 'api', 'prefix' => 'faqs'],
    function () {
        Route::get('find/{faq}', [FaqController::class, 'show']);
        Route::post('update/{faq}', [FaqController::class, 'update']);
        Route::get('delete/{faq}', [FaqController::class, 'destroy']);
    });

Route::group(['middleware' => 'api', 'prefix' => 'levels'],
    function () {
        Route::get('/', [LevelController::class, 'index']);
        Route::post('/', [LevelController::class, 'store']);
        Route::get('find/{level}', [LevelController::class, 'show']);
        Route::get('courses/{level}', [LevelController::class, 'courseList']);
        Route::get('delete/{id}', [LevelController::class, 'destroy']);
        Route::post('update/{id}', [LevelController::class, 'update']);
    });


Route::group(['middleware' => 'api', 'prefix' => 'resources'],
    function () {
        Route::post('/', [CourseResourceController::class, 'store']);
        Route::get('/course/{course}', [CreateCourseResourcesTable::class, 'index']);
        Route::post('update/{id}', [CourseResourceController::class, 'update']);
        Route::get('user/{user}', [CourseResourceController::class, 'show']);
        Route::get('delete/{id}', [CourseResourceController::class, 'destroy']);
    });

