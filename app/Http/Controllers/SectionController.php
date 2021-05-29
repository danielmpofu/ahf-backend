<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

//use App\Models\slideshow;

class SectionController extends Controller
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
        return $this->successResponse(Section::query()
            ->where('slideshow_id', $slideshow_id)
            ->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            ['slideshow_id' => 'required',
                'course_id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'number' => 'required']);

        if ($validator->fails()) {
            $slideshow = new  Section($validator->validated());
            $slideshow->created_by = $this->user->id;
            $slideshow->cover_pic = $request->cover_pic->store('public\course_resources');
            $slideshow->save();
            return $this->entityCreated($slideshow);
        } else {
            return $this->badRequest($validator->errors());
        }
    }

    public function show(Section $section)
    {
        return $this->successResponse($section);
    }

    public function update(Request $request, Section $slideshow)
    {
        //
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return $this->successResponse($section);
    }
}
