<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
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
        return $this->successResponse(Level::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['title' => 'string|required', 'description' => 'string|required']);

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {
            $level = new Level();
            $level->title = $request->title;
            $level->description = $request->description;
            $level->save();
            return $this->entityCreated($level);
        }
    }

    public function show(Level $level)
    {
        return $this->successResponse($level);
    }

    public function courseList(Level $level)
    {
        return $this->successResponse($level->courses()->get());
    }

    public function edit(Level $level)
    {

    }

    public function update(Request $request, Level $level)
    {
        //
    }

    public function destroy(Level $level)
    {
        //
    }
}
