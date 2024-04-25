<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];
        return response($res, 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();

if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'msg' => 'incorrect username or password'
            ], 401);
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        $res = [
            'user' => $user,
            'token' => $token
        ];

        return response($res, 201);
    }

    public function logout(Request $request)
{
    $user = auth()->user();

    if ($user) {
        auth()->logout();
        return response()->json(['message' => 'Logged out successfully from session.']);
    }

    $bearerToken = $request->bearerToken();
    if ($bearerToken) {
        $user = User::where('api_token', $bearerToken)->first();
        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully using token.']);
        }
    }

    return response()->json(['error' => 'Unauthenticated.'], 401);
}

}
