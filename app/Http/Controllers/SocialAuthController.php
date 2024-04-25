<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller 
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $user = Socialite::driver($provider)->stateless()->user();
        $user = User::firstOrCreate([
            'email' => $user->getEmail()
        ], [
            'name' => $user->getName(),
            'password' => bcrypt('password')
        ]);

        $token = $user->createToken('apiToken')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }
}