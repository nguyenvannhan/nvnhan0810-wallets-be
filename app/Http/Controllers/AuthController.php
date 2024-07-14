<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback()
    {
        $user = Socialite::driver('google')->stateless()->user();

        if ($user->email !== 'nguyenvannhan0810@gmail.com') {
            abort(403);
        }

        $dbUser = User::where('email', $user->email)->first();

        if (!$dbUser) {
            $dbUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
            ]);
        } else {
            if ($user->avatar !== $dbUser->avatar) {
                $dbUser->avatar = $user->avatar;

                $dbUser->save();
            }
        }

        Auth::login($dbUser, true);

        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('index');
    }
}
