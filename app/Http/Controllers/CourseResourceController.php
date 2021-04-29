<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseResource;
use App\Models\ResourceViews;
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

    public function index(Course $course)
    {
        $crs = $course->courseResources()->get();
        $cc = array();
        foreach ($crs as $cr) {
            $v = $cr->resourceViews();
            $f = array_merge(json_decode(json_encode($cr), true), ['views' => $v->count()]);
            array_push($cc, $f);
        }
        return $this->successResponse($cc);
    }


    public function addResourceView(Request $request)
    {
        $request->user_id = $this->user->id;
        $validator = Validator::make($request->all(), ['course_id' => 'required', 'resource_id' => 'required']);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {

            $v = ResourceViews::query()
                ->where('user_id', $this->user->id)
                ->where('course_id', $request->course_id)
                ->where('resource_id', $request->resource_id)
                ->get();

            if ($v->count() < 1) {
                $view = new ResourceViews(array_merge($request->all(), ['user_id' => $this->user->id]));
                $view->save();
                return $this->successResponse($view);
            } else {
                return $this->successResponse(['success' => false, 'message' => 'already viewed this item']);
            }
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'string|required',
                'description' => 'string|required',
                //'created_by' => 'required',
                'course_id' => 'required',
                'points' => 'required'
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
        $views = $resource->resourceViews();
        return $this->successResponse(
            array_merge(json_decode(json_encode($resource), true), ['views' => $views->count()])
        );
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
