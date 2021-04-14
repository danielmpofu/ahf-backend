<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseResourceController extends Controller
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
        return $this->successResponse(CourseResource::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'string|required',
                'description' => 'string|required',
                //'created_by' => 'required',
                'course_id' => 'required'
            ]);

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {
            $course = Course::query()->findOrFail($request->course_id);

            $courseResourceData = $request->all();
            $path = 'n/a';
            if (isset($request->path)) {
                $path = $request->path->store('public\course_resources');
                unset($request->all()['path']);
                $courseResourceData['file_extension'] = $request->path->extension();
                $courseResourceData['file_type'] = '.' . $request->path->extension();
                $courseResourceData['file_size'] = $request->path->getSize();

            }
            $courseResourceData['created_by'] = $this->user->id;
            $courseResourceData['path'] = $path;
            $resource = CourseResource::query()->create($courseResourceData);
            $course->courseResources()->save($resource);
            $this->user->courseResources()->save($resource);
            return $this->entityCreated($resource);
        }
    }

    public function show(CourseResource $resource)
    {
        return $this->successResponse($resource);
    }

    public function edit(CourseResource $courseResource)
    {
        //
    }

    public function update(Request $request, CourseResource $courseResource)
    {
        //
    }

    public function destroy(CourseResource $id)
    {
        $id->delete();
        return $this->successResponse($id);
    }
}
