<?php

namespace App\Http\Controllers;

use App\Models\SlideshowSection;
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
        return $this->successResponse(SlideshowSection::query()
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
                // 'number' => 'required'
            ]);

        if (!$validator->fails()) {
            $slideshow = new  SlideshowSection($validator->validated());
            $slideshow->created_by = $this->user->id;
            $slideshow->number = 0;

            $slideshow->save();
            return $this->entityCreated($slideshow);
        } else {
            return $this->badRequest($validator->errors());
        }
    }

    public function show(SlideshowSection $section)
    {
        return $this->successResponse($section);
    }

    public function update(Request $request, $section)
    {
        $res = SlideshowSection::query()->where('id', $section)->first();
        if ($res) {

            SlideshowSection::query()
                ->where('id', $res->id)
                ->update($request->all());

            return $this->successResponse($res->toArray());
        } else {
            return $this->notFound();
        }
    }

    public function destroy(SlideshowSection $section)
    {
        $section->delete();
        return $this->successResponse($section);
    }
}
