<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseResourceController;
use App\Http\Controllers\LevelController;
use Illuminate\Support\Facades\Route;

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
        Route::get('comment/{course}', [CourseController::class, 'comments']);
        Route::post('comment/{course}', [CourseController::class, 'comment']);
        Route::get('my-courses', [CourseController::class, 'myCourses']);
        Route::get('find/{id}', [CourseController::class, 'show']);
        Route::get('delete/{id}', [CourseController::class, 'destroy']);
        Route::get('resources/{course}', [CourseController::class, 'resources']);
        Route::post('update/{id}', [CourseController::class, 'update']);
        Route::post('faqs/{id}', [CourseController::class, 'add_faq']);
        Route::get('faqs/{id}', [CourseController::class, 'get_faq']);
    });

Route::group(['middleware' => 'api', 'prefix' => 'resources'],
    function () {
        Route::post('/', [CourseResourceController::class, 'store']);
        Route::get('find/{resource}', [CourseController::class, 'show']);
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

