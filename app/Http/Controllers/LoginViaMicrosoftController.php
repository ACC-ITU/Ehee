<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

class LoginViaMicrosoftController extends Controller
{
    public function redirect()
    {
        if(request()->inertia()) {
            return Inertia::location(Socialite::driver('microsoft')
                ->redirect()->getTargetUrl());
        }
        return Socialite::driver('microsoft')->redirect();
    }

    public function callback()
    {
        $microsoftUser = Socialite::driver('microsoft')->user();

        $user = User::updateOrCreate([
            'microsoft_id' => $microsoftUser->id,
        ], [
            'name' => $microsoftUser->name,
            'email' => $microsoftUser->email,
            'password' => Hash::make(Str::random(32)),
        ]);

        AUth::login($user);


        return redirect()->intended(route('vehicles.index', absolute: false));
    }
}
