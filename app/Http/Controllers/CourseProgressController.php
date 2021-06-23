<?php

namespace App\Http\Controllers;

use App\Models\AnswerSheets;
use App\Models\Course;
use App\Models\CourseProgress;
use App\Models\CourseResource;
use App\Models\Exercise;
use App\Models\ExerciseAttempts;
use App\Models\ResourceViews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseProgressController extends Controller
{

    protected $user;

    protected function guard()
    {
        return Auth::guard();

    }

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }


    public function index($course_id, $resource_id, $resource_type)
    {
        $progress = CourseProgress::query()
            ->where('course_id', $course_id)
            ->where('resource_id', $resource_id)
            ->where('user_id', $this->user->id)
            ->where('resource_type', $resource_type)
            ->get();

        return $this->successResponse($progress);
    }

    public function check($course_id)
    {
        $course = Course::query()->where('id', $course_id)->first();
        if ($course) {
            $resources = array();
            $exercises = array();
            $rss = CourseResource::query()->where('course_id', $course->id)->get();
            foreach ($rss as $rs) {
                $rv = ResourceViews::query()
                    ->where('user_id', $this->user->id)
                    ->where('resource_id', $rs->id)
                    ->first();

                $c = json_decode(json_encode($rs), true);

                if ($rv) {
//                    array_push($resources,
                    $c = array_merge($c, ['viewed' => true]);
                } else {
                    $c = array_merge($c, ['viewed' => false]);
                }
                array_push($resources, $c);

            }

            $ex = Exercise::query()->where('course_id', $course->id)->get();
            foreach ($ex as $e) {
                $att = ExerciseAttempts::query()
                    ->where('course_id', $course->id)
                    ->where('exercise_id', $e->id)
                    ->where('student_id', $this->user->id)
                    ->first();


                if ($att) {

                    $answer_sheets = AnswerSheets::query()
                        ->where('attempt_id', $att->id)
                        ->get();

                    $questions_count = $answer_sheets->count();
                    $correct_answers = 0;

                    foreach ($answer_sheets as $answer_sheet) {
                        if ($answer_sheet->correct == 1) {
                            $correct_answers = $correct_answers + 1;
                        }
                    }


                    array_push(
                        $exercises, array_merge(
                        json_decode(json_encode($e), true),
                        [
                            'attempted' => true,
                            'questions' => $questions_count,
                            'score' => $correct_answers
                        ]));

                } else {
                    array_push(
                        $exercises,
                        array_merge(
                            json_decode(json_encode($e), true),
                            ['attempted' => false, 'score' => 0]));
                }

            }

//            array_push($exercises,$ex);


            return $this->successResponse(['resources' => $resources, 'exercises' => $exercises]);
        } else {
            return $this->notFound();
        }
    }

    public function progress_overview()
    {
        $enrollements = $this->user->enrollments;
        $courses = array();
        foreach ($enrollements as $enrollement) {
            $cs = Course::query()->where('id', $enrollement->course_id)->first();
            if ($cs) {
                $cs->lectures = 10;
                array_push($courses, $cs);
            }
        }

        $progs = array();

        foreach ($courses as $course) {

            $ps = CourseProgress::query()
                ->where('course_id', $course->id)
                ->where('user_id', $this->user->id)
                ->get();

            $current = 0;
            $totalWeight = 0;
            $res = CourseResource::query()->where('course_id', $course->id)->get();
            foreach ($res as $re) {
                $totalWeight = $totalWeight + $re->points;
            }

            foreach ($ps as $p) {
                $current = $current + $p->points;
            }
            //get all resources and calculate the total weight
            //

            array_push($progs,
                array_merge(
                    ['course' => $course->title, 'course_id' => $course->id],
                    ['progress' => $current, 'total' => $totalWeight]));
        }

        return $this->successResponse($progs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['course_id' => 'required', 'resource_id' => 'required', 'resource_type' => 'required',]);

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {

            $progress = CourseProgress::query()
                ->where('course_id', $request->course_id)
                ->where('resource_id', $request->resource_id)
                ->where('user_id', $this->user->id)
                ->where('resource_type', $request->resource_type)
                ->get();

            if (count($progress) > 0) {
                return $this->successResponse($progress);
            } else {
                $p = new CourseProgress(array_merge($request->all(), ['user_id' => $this->user->id]));
                $p->save();
                return $this->successResponse($p);
            }

        }
    }

    public function show($course_id)
    {
        $ps = CourseProgress::query()
            ->where('course_id', $course_id)
            ->where('user_id', $this->user->id)
            ->get();
    }

    public function edit(CourseProgress $courseProgress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CourseProgress $courseProgress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseProgress $courseProgress)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CourseProgress $courseProgress
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseProgress $courseProgress)
    {
        //
    }
}
