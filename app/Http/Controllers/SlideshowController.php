<?php

namespace App\Http\Controllers;

use App\Models\Slideshow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SlideshowController extends Controller
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

    public function index($course_id)
    {
        return $this->successResponse(Slideshow::query()
            ->where('course_id', $course_id)
            ->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            ['course_id' => 'required', 'title' => 'required', 'description' => 'required', 'cover_pic' => 'required']);
        if ($validator->fails()) {
            $slideshow = new  Slideshow($validator->validated());
            $slideshow->created_by = $this->user->id;
            $slideshow->cover_pic = $request->cover_pic->store('public\course_resources');
            $slideshow->save();
            return $this->entityCreated($slideshow);
        } else {
            return $this->badRequest($validator->errors());
        }
    }

    public function show(Slideshow $slideshow)
    {
      return $this->successResponse($slideshow);
    }


    public function update(Request $request, Slideshow $slideshow)
    {
        //
    }

    public function destroy(Slideshow $slideshow)
    {
        $slideshow->delete();
        return $this->successResponse($slideshow);
    }
}
