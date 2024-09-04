<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                'provider_id' => $socialUser->getId(),
                'provider' => $provider,
                'password' => bcrypt(Str::random(24)),
            ]
        );

        Auth::login($user);

        $user['provider'] = $provider; 

        // dd($user);

        Mail::to($user->email)->send(new WelcomeEmail($user));

        return redirect()->route('home');
    }
}