<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                Auth::login($user);

                return redirect()->intended('dashboard');
            } else {
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => encrypt('password'),
                ]);

                Auth::login($newUser);

                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
