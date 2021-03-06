<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AuthRequest\SeekerRegister;
use App\Rules\RegisterSeeker;
use Illuminate\Support\Facades\Hash;
use App\User;

class SeekerAuth extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $userData = [
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role_id'],
        ];
        return $this->respondWithToken($token, $userData);
    }


    public function register(SeekerRegister $request)
    {
        $user = new User();
        $user->name     = $request['name'];
        $user->role_id  = 4;
        $user->email    = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();

        return response()->json(['success' => $request->all()], 200);
    }


    public function me()
    {
        return response()->json(auth()->guard('api')->user());
    }


    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        $user = auth('api')->user();
        $userData = [
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role_id'],
        ];
        return $this->respondWithToken(auth('api')->refresh(), $userData);
    }


    protected function respondWithToken($token, $userData)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'userData'   => $userData
        ]);
    }
}
