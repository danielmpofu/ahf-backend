<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __constructor()
    {
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

        $user = User::query()->create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );

//         $f = $this->login($request);

        return $this->entityCreated($this->login($request));


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
