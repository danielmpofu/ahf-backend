<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseProgressController;
use App\Http\Controllers\CourseResourceController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\SlideshowController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

Route::group(['middleware' => 'api', 'prefix' => 'users'], function () {
    Route::get('/disable/{user}', [UsersController::class, 'disable']);
    Route::post('/invite/', [UsersController::class, 'invite']);
    Route::get('/invitations/', [UsersController::class, 'invitations']);
    Route::get('/', [UsersController::class, 'index']);
    Route::get('/{user}', [UsersController::class, 'show']);
    Route::delete('/{user}', [UsersController::class, 'destroy']);
});

Route::post('/run-command', function (Request $request) {
    $val = Validator::make($request->all(), ['command' => 'required']);
    if ($val->fails()) {
        return response()->json($val->errors(), 400);
    } else {
        $cmd = $request->command;
        $exitCode = Artisan::call($cmd);
        return response()->json(['exitcode' => $exitCode, 'command' => '"' . $cmd . '"'], 200);
    }
});

Route::group(['middleware' => 'api', 'prefix' => 'exercises'],
    function () {
        Route::get('available', [ExerciseController::class, 'myExercises']);
        Route::post('add-question', [ExerciseController::class, 'addQuestion']);
        Route::post('get-questions', [ExerciseController::class, 'getQuestion']);
        Route::post('attempt', [ExerciseController::class, 'attempt_exercise']);
        Route::post('save-answer', [ExerciseController::class, 'addAnswer']);
        Route::post('get-answer-sheets', [ExerciseController::class, 'getAnswerSheets']);
        Route::get('get-one/{exercise}', [ExerciseController::class, 'show']);
        Route::post('update/{exercise}', [ExerciseController::class, 'update']);
        Route::post('update-mcq/{mcq}', [ExerciseController::class, 'updateMcq']);
        Route::get('delete/{exercise}', [ExerciseController::class, 'destroy']);
        Route::get('find-by-course/{course}', [ExerciseController::class, 'findByCourse']);
        Route::get('/', [ExerciseController::class, 'index']);
        Route::post('/', [ExerciseController::class, 'store']);
    });

Route::group(['middleware' => 'api', 'namespace' => 'Api\Http\Controllers', 'prefix' => 'auth'],
    function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('invitation/{token}', [AuthController::class, 'getInvitation']);
        Route::post('reg', [AuthController::class, 'register']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('update-password', [AuthController::class, 'changePassword']);
        Route::post('update-profile-pic', [AuthController::class, 'updateProfilePic']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('check_existence', [AuthController::class, 'check_existence']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

Route::group(['middleware' => 'api', 'prefix' => 'courses'],
    function () {
        Route::post('new', [CourseController::class, 'create']);
        Route::get('enrolled-courses', [CourseController::class, 'myCourses']);
        Route::get('my-creations', [CourseController::class, 'myCreatedCourses']);
        Route::get('enroll/{course}', [CourseController::class, 'enrollCourse']);
        Route::get('find/{id}', [CourseController::class, 'show']);
        Route::get('curriculum/{course_id}', [CourseController::class, 'getCurriculum']);
        Route::get('delete/{id}', [CourseController::class, 'destroy']);
        Route::post('update/{id}', [CourseController::class, 'update']);
        Route::post('faqs/{id}', [CourseController::class, 'add_faq']);
        Route::get('faqs/{id}', [CourseController::class, 'get_faq']);
        Route::get('all', [CourseController::class, 'index']);
    });


Route::group(['middleware' => 'api', 'prefix' => 'resources'],
    function () {
        Route::post('/', [CourseResourceController::class, 'store']);
        Route::get('/{course}', [CourseResourceController::class, 'index']);
        Route::delete('delete/{id}', [CourseResourceController::class, 'destroy']);
        Route::get('find/{resource}', [CourseResourceController::class, 'show']);
        Route::post('update/{resource}', [CourseResourceController::class, 'update']);
        Route::post('view', [CourseResourceController::class, 'addResourceView']);
    });

Route::group(['middleware' => 'api', 'prefix' => 'progress'],
    function () {
        Route::get('check/{course_id}/{resource_id}/{resource_type}', [CourseProgressController::class, 'index']);
        Route::get('details/{course_id}', [CourseProgressController::class, 'check']);
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
        Route::get('course/{course}', [CreateCourseResourcesTable::class, 'index']);
        Route::post('update/{courseource}', [CourseResourceController::class, 'update']);
        Route::get('user/{user}', [CourseResourceController::class, 'show']);
        Route::get('delete/{id}', [CourseResourceController::class, 'destroy']);
    });

Route::group(['middleware' => 'api', 'prefix' => 'slideshows'],
    function () {
        Route::post('/', [SlideshowController::class, 'store']);
        Route::get('list/{course_id}', [SlideshowController::class, 'index']);
        Route::get('find/{id}', [SlideshowController::class, 'show']);
        Route::post('update/{slideshow}', [SlideshowController::class, 'update']);
        Route::get('delete/{slideshow}', [SlideshowController::class, 'destroy']);
    });

Route::group(['middleware' => 'api', 'prefix' => 'slideshow_sections'],
    function () {
        Route::post('/', [SectionController::class, 'store']);
        Route::get('list/{slideshow_id}', [SectionController::class, 'index']);
        Route::get('find/{section}', [SectionController::class, 'show']);
        Route::post('update/{section}', [SectionController::class, 'update']);
        Route::get('delete/{section}', [SectionController::class, 'destroy']);
    });

Route::group(['middleware' => 'api', 'prefix' => 'slides'],
    function () {
        Route::post('/', [SlideController::class, 'store']);
        Route::get('list/{section_id}/{course_id}', [SlideController::class, 'index']);
        Route::get('find/{id}', [SlideController::class, 'show']);
        Route::get('delete/{slideshow}', [SlideController::class, 'destroy']);
    });
