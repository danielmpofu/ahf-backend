<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
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
        $todos = $this->user->todos()->get(['title', 'body', 'completed', 'created_by']);
        //   $todos = Todo::all();
        return $this->successResponse($todos);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'string|required|between:3,50',
            'body' => 'required|string',
            'completed' => 'boolean|required',

        ]);

        if (!$validator->fails()) {

            //$todo = $validator->validated();
            $todo = new Todo();
            $todo->title = $request->title;
            $todo->body = $request->body;
            $todo->completed = $request->completed;
            $this->user->todos()->save($todo);

            return $this->entityCreated($todo);

        } else {
            return $this->badRequest($validator->errors());
        }

    }


    public function show($id)
    {
        $todo = Todo::query()->findOrFail($id);
        return $this->successResponse($todo);
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::query()->findOrFail($id);
        $todoData = $request->all();
        $todo['title'] = $todoData['title'];
        $todo['body'] = $todoData['body'];
        $todo['completed'] = $todoData['completed'];
        // $todo['']=$todoData[''];
        $todo->save();
        return $this->successResponse($todo);
    }

    public function destroy($id)
    {
        $todo = Todo::query()->findOrFail($id);
        $todo->delete();
        $arr = ['message' => 'todo deleted', 'todo' => $todo];
        return $this->successResponse($arr);
    }
}
