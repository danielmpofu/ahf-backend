<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
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

    public function index($entity_id, $entity_type)
    {
        $comments = Comment::query()
            ->where('entity_id', $entity_id)
            ->where('entity_type', $entity_type)
            ->get();

        return $this->successResponse($comments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['message' => 'string|required']);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {
            $c = new Comment();
            $c->message = $request->message;
            $c->user_id = $this->user->id;
            $c->entity_id = $request->entity_id;
            $c->entity_type = $request->entity_type;
            $c->save();
            return $this->entityCreated($c);
        }
    }

    public function create()
    {
        //
    }

    public function show(Comment $comment)
    {
        //
    }

    public function edit(Comment $comment)
    {
        //
    }

    public function update(Request $request, Comment $comment)
    {
        //
    }

    public function destroy(Comment $comment)
    {
        //
    }
}
