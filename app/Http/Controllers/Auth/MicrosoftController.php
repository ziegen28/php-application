<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Assessment;
use App\Models\Resume;
use Illuminate\Support\Facades\Auth;

class MicrosoftController extends Controller
{
    /* =====================================================
       REDIRECT TO MICROSOFT
    ===================================================== */
    public function redirectToProvider()
    {
        return Socialite::driver('microsoft')
            ->stateless()
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /* =====================================================
       MICROSOFT CALLBACK
    ===================================================== */
    public function handleProviderCallback()
    {
        try {
            $msUser = Socialite::driver('microsoft')
                ->stateless()
                ->user();
        } catch (\Exception $e) {
            return redirect('/login')
                ->with('error', 'Microsoft login failed. Please try again.');
        }

        /* =====================================================
           GET EMAIL SAFELY
        ===================================================== */
        $email = $msUser->getEmail()
            ?? ($msUser->user['userPrincipalName'] ?? null);

        if (!$email) {
            return redirect('/login')
                ->with('error', 'Unable to read Microsoft account email.');
        }

        /* =====================================================
           INVITE-ONLY ACCESS (FIXED UX)
        ===================================================== */
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect('/login')
                ->with('error', 'You are not authorized to attend this assessment. Please contact the administrator.');
        }

        /* =====================================================
           UPDATE USER INFO
        ===================================================== */
        $user->update([
            'name'         => $msUser->getName() ?? $user->name,
            'microsoft_id' => $msUser->getId(),
        ]);

        Auth::login($user);

        /* =====================================================
           ADMIN FLOW
        ===================================================== */
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        /* =====================================================
           USER FLOW (STRICT & SAFE)
        ===================================================== */

        // 1️⃣ Assessment completed → dashboard
        $completed = Assessment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->exists();

        if ($completed) {
            return redirect()->route('user.dashboard');
        }

        // 2️⃣ Active assessment → dashboard (resume button shows resume)
        $active = Assessment::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('end_time', '>', now())
            ->exists();

        if ($active) {
            return redirect()->route('user.dashboard');
        }

        // 3️⃣ Resume not uploaded → resume upload
        $resume = Resume::where('user_id', $user->id)->first();

        if (!$resume) {
            return redirect()->route('resume.upload');
        }

        // 4️⃣ Resume uploaded, no assessment yet → dashboard
        return redirect()->route('user.dashboard');
    }
}
