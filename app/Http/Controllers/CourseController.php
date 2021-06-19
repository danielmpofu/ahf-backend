<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseResource;
use App\Models\Enrollment;
use App\Models\Exercise;
use App\Models\Faq;
use App\Models\Level;
use App\Models\Slideshow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
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
        //return $this->successResponse('lol');
        $courses = Course::all();
        $outputData = array();
        foreach ($courses as $course) {
            $t = User::query()->findOrFail($course->instructor_id);
            $e = count($course->users()->get());
            $c = json_decode(json_encode($course), true);
            $c['tutor'] = $t->first_name . ' ' . $t->last_name;
            $c['enrollments'] = $e;

            $en = Enrollment::query()
                ->where('course_id', $course->id)
                ->where('user_id', $this->user->id)
                ->get();

            if (count($en) != 0) {
                $c['enrolled'] = true;
            } else {
                $c['enrolled'] = false;
            }

            array_push($outputData, $c);
        }
        return $this->successResponse($outputData);
    }


    public function add_faq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'question' => 'required',
            'answer' => 'required'
        ]);

        if (!$validator->fails()) {
            $faq = new Faq();
            $faq->course_id = $request->all()['course_id'];
            $faq->question = $request->all()['question'];
            $faq->answer = $request->all()['answer'];
            $faq->created_by = $this->user->id;
            $faq->save();
            return $this->entityCreated($faq);

        } else {
            return $this->badRequest($validator->errors());
        }
    }

    public function get_faq($cid)
    {
        // $course = Course::query()->get($cid);
        $faqs = Faq::query()->where('course_id', $cid)->get();
        return $this->successResponse($faqs);
    }

    //todo come back here ma one apa
    public function available()
    {
        $courses = Course::query()->where('level', $this->user->level)->get();
        return $this->successResponse($courses);
    }

    public function myCourses()
    {
        $enrolments = $this->user->enrollments;//->courses();
        $courses = array();
        foreach ($enrolments as $enrolment) {
            $course = Course::query()->where('id', $enrolment->course_id)->first();
            if ($course) {
                array_push($courses, $course);
            }
        }
        return $this->successResponse($courses);
    }

    public function myCreatedCourses()
    {
        $courses = Course::query()->where('instructor_id', $this->user->id)->get();
        return $this->successResponse($courses);
    }

    public function enrollCourse($course)
    {
        $course = Course::query()->findOrFail($course);
        $en = Enrollment::query()
            ->where('course_id', $course->id)
            ->where('user_id', $this->user->id)
            ->get();

        if (count($en) != 0) {
            $en[0]->delete();
            return $this->successResponse(['message' => 'Un enrolled', $en]);
        } else {
            $enr = new Enrollment();
            $enr->user_id = $this->user->id;
            $enr->course_id = $course->id;
            $enr->save();
            return $this->successResponse($enr);
        }

    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|required|between:3,100|unique:courses',
            'description' => 'required|string',
            'level' => 'required|string',
            'duration' => 'required',
            'content' => 'required',
            'entry_requirements' => 'required|string',
            'optional' => 'required|string',
        ]);

        if (!$validator->fails()) {

            $f = $request->all();
            unset($f['path']);
            $course = new Course($f);
            $course->title = $request->title;
            $course->description = $request->description;
            $course->entry_requirements = $request->entry_requirements;
            $course->optional = $request->optional;
            $course->instructor_id = $this->user->id;

            $level = Level::query()->findOrFail($request->level);

            $course->cover_image = $request->cover_image->store('public/images');

            $level->courses()->save($course);

            $this->user->courses()->save($course);
            return $this->entityCreated($course);

        } else {
            return $this->badRequest($validator->errors());
        }
    }

    public function show($id)
    {
        $course = Course::query()->findOrFail($id);

        $instructor = $course->instructor;
        $students = array();

        $enrollments = $course->users;

        foreach ($enrollments as $enrollment) {
            array_push($students, User::query()->findOrFail($enrollment->user_id));
        }

        $resources = $course->courseResources;

        $c = json_decode(json_encode($course), true);
        unset($c['course_resources']);
        $en = Enrollment::query()
            ->where('course_id', $course->id)
            ->where('user_id', $this->user->id)
            ->get();

        if (count($en) != 0) {
            $c['enrolled'] = true;
        } else {
            $c['enrolled'] = false;
        }

        $courseData = array(
            "course" => $c,
            "instructor" => $instructor,
            "students" => $students,
            "resources" => $resources
        );
        return $this->successResponse($courseData);
    }


    public function comments($course)
    {
        $courseF = Course::query()->findOrFail($course);

        $comments = array();

        foreach ($courseF->comments as $c) {
            $s = json_decode(json_encode($c), true);
            $u = User::query()->findOrFail($c->user_id);
            $s['full_name'] = $u->first_name . ' ' . $u->last_name;
            $s['username'] = $u->username;
            array_push($comments, $s);
        }

        return $this->successResponse($comments);
    }

    public function update(Request $request, $id)
    {
        $course = Course::query()->findOrFail($id);
        $course->title = $request->title;
        $course->description = $request->description;
        $course->entry_requirements = $request->entry_requirements;
        $course->optional = $request->optional;
        $course->content = $request->contents;

        if (isset($request->cover_image)) {
            //todo comeback here and add some code to remove the old file
            $course->cover_image = $request->cover_image->store('images');
        }

        $this->user->courses()->save($course);
        return $this->successResponse($course);
    }


    public function getCurriculum($course_id)
    {
        $resorces = CourseResource::query()
            ->where('course_id', $course_id)
            ->get();

        $slides = Slideshow::query()
            ->where('course_id', $course_id)
            ->get();

        $exs = Exercise::query()
            ->where('course_id', $course_id)
            ->get();

        return $this->successResponse(
            ['resources' => $resorces, 'slides' => $slides, 'exercises' => $exs,]
        );

    }

    public function destroy($id)
    {
        $course = Course::query()->findOrFail($id);
        $course->delete();
    }
}
