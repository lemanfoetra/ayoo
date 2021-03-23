<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest\OwnerRegister;
use Illuminate\Support\Facades\Auth;
use App\Owner;
use Illuminate\Support\Facades\Hash;


class OwnerAuth extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:owner', ['except' => ['login', 'register']]);
    }


    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('owner')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth('owner')->user();
        $userData = [
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role_id'],
        ];
        return $this->respondWithToken($token, $userData);
    }


    public function register(OwnerRegister $request)
    {
        $owner = new Owner();
        $owner->name     = $request['name'];
        $owner->role_id  = 3;
        $owner->email    = $request['email'];
        $owner->password = Hash::make($request['password']);
        $owner->save();

        return response()->json(['success' => $request->all()], 200);
    }


    public function me()
    {
        return response()->json(auth('owner')->user());
    }


    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->respondWithToken(auth('owner')->refresh(), []);
    }


    protected function respondWithToken($token, $userData)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('owner')->factory()->getTTL() * 60,
            'userData'   => $userData
        ]);
    }
}
