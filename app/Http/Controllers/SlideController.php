<?php

namespace App\Http\Controllers;


use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SlideController extends Controller
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

    public function index($slideshow_id)
    {
        return $this->successResponse(Slide::query()
            ->where('slideshow_id', $slideshow_id)
            ->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'image_url' => 'required',
                'audio_url' => 'required',
                'position' => 'required',
                'slideshow_id' => 'required',
                'section_id' => 'required',
                'course_id' => 'required',
                'title' => 'required',
                'description' => 'required',
            ]);

        if ($validator->fails()) {
            $slideshow = new  Slide($validator->validated());
            $slideshow->created_by = $this->user->id;
            $slideshow->cover_pic = $request->cover_pic->store('public\course_resources');
            $slideshow->save();
            return $this->entityCreated($slideshow);
        } else {
            return $this->badRequest($validator->errors());
        }
    }

    public function show(Slide $slideshow)
    {
        return $this->successResponse($slideshow);
    }

    public function update(Request $request, Slide $slideshow)
    {
        //
    }

    public function destroy(Slide $slideshow)
    {
        $slideshow->delete();
        return $this->successResponse($slideshow);
    }
}
