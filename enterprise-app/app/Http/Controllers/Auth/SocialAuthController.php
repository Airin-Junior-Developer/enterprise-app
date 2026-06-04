<?php
// app/Http/Controllers/Auth/SocialAuthController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): JsonResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            return response()->json([
                'message' => 'Account not found. Please contact your system administrator.',
            ], 403);
        }

        $user->update(['google_id' => $googleUser->getId()]);

        $token = $user->createToken('google-sso')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user->load(['branch', 'position']),
        ]);
    }
}
