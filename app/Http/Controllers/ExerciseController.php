<?php

namespace App\Http\Controllers;

use App\Models\AnswerSheets;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Exercise;
use App\Models\ExerciseAttempts;
use App\Models\Mcq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExerciseController extends Controller
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

    public function index()
    {
        return $this->successResponse(Exercise::all());
    }

    public function myExercises()
    {
        $enrolmemts = Enrollment::query()->where('user_id', $this->user->id)->get();
        $exs = array();
        $exs2 = array();

        foreach ($enrolmemts as $enrolmt) {
            $ex = json_decode(json_encode(Exercise::query()->where('course_id', $enrolmt->course_id)->get()), true);

            array_push($exs, $ex);
        }

        foreach ($exs as $ex) {
            foreach ($ex as $e) {
                $c = Course::query()->findOrFail($e['course_id']);
                array_push($exs2, array_merge($e, ['img_url' => $c->cover_image, 'questions' => $this->getQuestions($e['id'])]));
            }
        }

        return $this->successResponse($exs2);

    }

    public function getQuestions($ex)
    {
        $c = Mcq::query()->where('exercise_id', $ex)->count('*');
        return $c;
    }

    public function findByCourse($course)
    {
        $exs = Exercise::query()->where('course_id', $course)->get();
        $a = array();
        foreach ($exs as $ex) {
            $c = Course::query()->findOrFail($course);
            array_push($a, array_merge(json_decode(json_encode($ex), true),
                [
                    'course' => $c->id,
                    'questions' => $this->getQuestions($ex->id)
                ]));
        }
        return $this->successResponse($exs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'course_id' => 'required',
            'title' => 'required',
            'attempts' => 'required',
            'pass_mark' => 'required',
            'duration' => 'required',
            'description' => 'required',
            'contribution' => 'required',
            'final_test' => 'required',
            'requirements' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {
            $exercise = new Exercise(array_merge($request->all(),
                ['created_by' => $this->user->id, 'student_id' => '0']
            ));
            $exercise->save();
            $mcq = $exercise->mcqs->count();
            $e = json_decode(json_encode($exercise), true);
            return $this->successResponse(array_merge($e, ['questions' => $mcq]));
        }
    }

    public function show(Exercise $exercise)
    {
        if (!$exercise) {
            return $this->notFound();
        } else {
            return $this->successResponse(array_merge(
                array_merge(json_decode(json_encode($exercise),
                    true), ['questions' => $this->getQuestions($exercise->id)])));
        }
    }

    //public function edit(Exercise $exercise) {}

    public function update(Request $request, $exercise)
    {
        $res = Exercise::query()->where('id', $exercise)->first();
        if ($res) {
            Exercise::query()
                ->where('id', $res->id)
                ->update($request->all());

            return $this->successResponse($res->toArray());
        } else {
            return $this->notFound();
        }
    }

    public function updateMcq(Request $request, $mcq)
    {
        $res = Mcq::query()->where('id', $mcq)->first();
        if ($res) {
            Mcq::query()
                ->where('id', $res->id)
                ->update($request->all());

            return $this->successResponse($res->toArray());
        } else {
            return $this->notFound();
        }
    }

    public function destroy(Exercise $exercise)
    {
        $exercise->delete();
        return $this->successResponse($exercise);
    }

    public function attempt_exercise(Request $request)
    {
        $validator = Validator::make($request->all(),
            ['student_id' => 'required',
                'course_id' => 'required',
                'exercise_id' => 'required',
                'score' => 'required',
                'questions' => 'required',
                'pass_mark' => 'required',
            ]);

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {
            $e = Exercise::query()->where('id', $request->exercise_id)
                ->first();
            if ($e) {
                $f = $request->all();
                $my_attempts = ExerciseAttempts::query()
                    ->where('student_id', $this->user->id)
                    ->where('course_id', $f['course_id'])
                    ->where('exercise_id', $f['exercise_id'])
                    ->get()
                    ->count();

                if ($my_attempts >= $e->attempts) {
                    return $this->successResponse(['message' => 'you are no longer allowed to attempt this exercise']);
                } else {
                    $eA = new ExerciseAttempts($request->all());
                    $eA->save();
                    return $this->entityCreated($eA);
                }


            } else {
                return $this->notFound();
            }


        }

    }

    //////////////questions
    public function addQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), []);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {
            $mcq = new Mcq(array_merge($request->all(),
                ['created_by' => $this->user->id,
                    'user_id' => $this->user->id]
            ));
            $mcq->save();
            return $this->successResponse($mcq);
        }
    }

    public function addAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attempt_id' => 'required',
            'course_id' => 'required',
            'exercise_id' => 'required',
            'question' => 'required',
            'answer' => 'required',
            'provided_answer' => 'required',
            'answer_explanation' => 'required',
            'choice_one' => 'required',
            'choice_two' => 'required',
            'choice_three' => 'required',
            'choice_four' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {

            $a = $request->all();
            $a['question_id'] = $a['id'];
            $a['user_id'] = $this->user->id;
            $a['correct'] = $a['answer'] == $a['provided_answer'];


            if ($a['correct'] == 1) {
                $att = ExerciseAttempts::query()
                    ->where('id', $a['attempt_id'])
                    ->first();
                if ($att) {
                    $att->score = $att->score + 1;
                    $att->save();
                }
            }

            unset($a['id']);

            $mcq = new AnswerSheets(array_merge($a,
                ['created_by' => $this->user->id,
                    'user_id' => $this->user->id]
            ));
            $mcq->save();
            return $this->successResponse($mcq);
        }
    }

    public function getQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), ['course_id' => 'required', 'exercise_id' => 'required']);

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {
            $mcqs = Mcq::query()->where('course_id', $request->course_id)
                ->where('exercise_id', $request->exercise_id)
                ->get();

            return $this->successResponse($mcqs);
        }


    }

    public function getAnswerSheets(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'attempt_id' => 'required',
            'exercise_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {
            $mcqs = AnswerSheets::query()->where('course_id', $request->course_id)
                ->where('exercise_id', $request->exercise_id)
                ->where('attempt_id', $request->attempt_id)
                ->get();
            return $this->successResponse($mcqs);
        }
    }
}
