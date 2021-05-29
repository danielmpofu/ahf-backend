<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInvitation;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __constructor()
    {
        $exitCode = Artisan::call('cache:clear');
        return $this->respondWithToken($token);
        $this->middleware('auth:api', ['except' => ['login', 'register']]);

    }

    protected function guard()
    {
        return Auth::guard();
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'email|required', 'password' => 'string|required']);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        }
        $tokenValidity = 60 * 24 * 3;
        $this->guard()->factory()->setTTL($tokenValidity);
        if (!$token = $this->guard()->attempt($validator->validated())) {
            return response()->json(['errors' => 'unauthorized'], 401);
        }

        return $this->respondWithToken($token);

    }

    public function check_existence(Request $request)
    {
        $valArray = array();
        array_push($valArray, ['phone' => 'required|unique:users']);
        array_push($valArray, ['username' => 'required|string|between:5,30|unique:users']);
        array_push($valArray, ['email' => 'required|email|unique:users',]);

        $validator = null;
        if (isset($request->username)) {
            $validator = Validator::make($request->all(), ['username' => 'required|string|between:5,30|unique:users']);
            if ($validator->fails()) {
                return $this->badRequest($validator->errors()->get('username'));
            } else {
                return $this->successResponse($validator->errors());
            }
        }

        if (isset($request->email)) {
            $validator = Validator::make($request->all(), ['email' => 'required|email|unique:users',]);
            if ($validator->fails()) {
                return $this->badRequest($validator->errors()->get('email'));
            } else {
                return $this->successResponse($validator->errors());
            }
        }

        if (isset($request->phone)) {
            $validator = Validator::make($request->all(), ['phone' => 'required|unique:users']);
            if ($validator->fails()) {
                return $this->badRequest($validator->errors()->get('phone'));
            } else {
                return $this->successResponse($validator->errors());
            }
        }


    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'username' => 'required|string|between:5,30|unique:users',
                'email' => 'required|email|unique:users',
                'first_name' => 'required',
                'last_name' => 'required',
                'user_type' => 'required',
                'phone' => 'required|unique:users',
                'password' => 'required|string|between:5,10'
            ]);

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        }

        $u = new User($validator->validated());
        $u->pic_url = 'profile_pictures/default.png';
        $u->password = bcrypt($request->password);
        $u->save();
//        $user = User::query()->create(
//            array_merge(
//                $validator->validated(),
//                ['password' => bcrypt($request->password),'pic_url'=>'profile_pictures/default.png']
//            )
//        );

        return $this->entityCreated($this->login($request));
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'username' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'user_type' => 'required',
                'id' => 'required'
            ]);

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        }

        $user = User::query()->findOrFail($request->id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->user_type = $request->user_type;
        $user->address = $request->address;
        $user->title = $request->title;
        $user->dob = $request->dob;
        $user->gender = $request->gender;
//        $user->id = $request->user_id;

        $user->save();

        //$user = User::query()->create(array_merge( $validator->validated(),)

        return $this->entityCreated($user);
    }

    public function getInvitation($token)
    {
        $invitation = UserInvitation::query()->where('key', $token)->first();
        if ($invitation) {
            $user = User::query()->findOrFail($invitation->invited_by);
            $invitation->invited_by = $user->first_name . ' ' . $user->last_name;
            return $this->successResponse($invitation);
        } else {
            return $this->badRequest(['message' => 'The invitation key you have provided is invalid or has expired please provide another one and try again']);
        }

    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'old_password' => 'required',
                'new_password' => 'required',
                'confirm_password' => 'required',
                'id' => 'required'
            ]);

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {
            $oldP = bcrypt(trim($request->old_password));
            $newP = bcrypt(trim($request->new_password));
            $confirmP = bcrypt(trim($request->confirm_password));
            $user = User::query()->findOrFail($request->id);

            if ($user->password == $oldP) {
                if ($newP == $confirmP) {
                    $user->password = $newP;
                    $user->save();
                    return $this->successResponse($user);
                } else {
                    return $this->badRequest(['message' => 'new passwords are not matching']);
                }
            } else {
                return $this->successResponse(['message' => 'old password is not correct'
                    , 'user_password' => $user->password,
                    'provided_password' => $oldP,
                    'new_password' => $newP,
                    'confirm_password' => $confirmP

                ]);
            }
        }
    }

    public function updateProfilePic(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'picture' => 'required',
                'id' => 'required'
            ]);
        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        } else {
            $user = User::query()->findOrFail($request->id);
            $path = $request->picture->store('public\profile_pictures');
            $user->pic_url = $path;
            $user->save();
            return $this->successResponse($user);
        }
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json(['message' => 'user logged out successfully'], 200);
    }

    public function profile()
    {
        $user = $this->guard()->user();
        return $this->successResponse($user);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->$this->refresh());
    }

    private function respondWithToken($token)
    {
        return response()->json(
            [
                'token' => $token,
                'token_type' => 'bearer',
                'user_data' => $this->guard()->user(),
                'token_validity' => $this->guard()->factory()->getTTL() * 60
            ]
        );
    }

}
