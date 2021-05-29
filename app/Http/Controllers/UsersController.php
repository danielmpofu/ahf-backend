<?php

namespace App\Http\Controllers;

use App\Mail\UserInvitationEmail;
use App\Models\AnswerSheets;
use App\Models\Comment;
use App\Models\Course;
use App\Models\CourseProgress;
use App\Models\CourseResource;
use App\Models\Enrollment;
use App\Models\Exercise;
use App\Models\ExerciseAttempts;
use App\Models\Faq;
use App\Models\Mcq;
use App\Models\ResourceViews;
use App\Models\Student;
use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
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
        $users = json_decode(json_encode(User::all()), true);
        $u = array();
        $s = $this->user->id;
        foreach ($users as $user) {
            if ($user['id'] != $s) {
                array_push($u, $user);
            }
        }
        return $this->successResponse($u);
    }

    public function invitations()
    {
        return $this->successResponse(UserInvitation::all());
    }


    public function invite(Request $request)
    {
        $val = Validator::make($request->all(), [
            'email' => 'required|email',
            'status' => 'required',
            'expiry' => 'required',
            'invited_to' => 'required',
            'message' => 'required',
            'user_role' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        if ($val->fails()) {
            return $this->badRequest($val->errors());
        } else {
            $inv = new UserInvitation($val->validated());
            $inv->invited_by = $this->user->id;
            $inv->key = UserInvitation::generateKey();
            $inv->email_status = 'sent';
            $inv->save();
            Mail::to($inv->email)->send(new UserInvitationEmail($inv));
            return $this->entityCreated($inv);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }


    public function show(User $user)
    {
        return $this->successResponse($user);
    }


    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {

        AnswerSheets::query()->where('user_id', $user->id)->delete();

        UserInvitation::query()->where('invited_by', $user->id)->delete();

        ResourceViews::query()->where('user_id', $user->id)->delete();

        Faq::query()->where('created_by', $user->id)->delete();

        Mcq::query()->where('created_by', $user->id)->delete();

        Mcq::query()->where('user_id', $user->id)->delete();

        Comment::query()->where('user_id', $user->id)->delete();

        CourseProgress::query()->where('user_id', $user->id)
            ->delete();

        Enrollment::query()->where('user_id', $user->id)
            ->delete();

        Exercise::query()->where('student_id', $user->id)
            ->delete();

        ExerciseAttempts::query()->where('student_id', $user->id)
            ->delete();

        CourseResource::query()->where('created_by', $user->id)
            ->delete();

        Course::query()->where('instructor_id', $user->id)
            ->delete();

        $user->delete();

        return $this->successResponse($user);
    }

    public function disable(User $user)
    {
        if ($user->active == User::$active_user) {
            $user->active = User::$inactive_user;
        } else if ($user->active == User::$inactive_user) {
            $user->active = User::$active_user;
        } else {
            $user->active = User::$inactive_user;
        }
        $user->save();
        return $this->successResponse($user);
    }
}
