<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MicrosoftController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('microsoft')
            ->stateless()
                ->with([
                'prompt' => 'select_account'
            ])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        $msUser = Socialite::driver('microsoft')
            ->stateless()
            ->user();

        $email = $msUser->getEmail()
            ?? $msUser->user['userPrincipalName'];

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $msUser->getName(),
                'microsoft_id' => $msUser->getId(),
                'role' => 'user'
            ]
        );

        Auth::login($user);

        return redirect()->route('resume.upload');
    }
}
