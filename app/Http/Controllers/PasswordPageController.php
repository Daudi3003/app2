<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Password reset screens.
 *
 * The existing backend has no password-reset flow, so these render the
 * finished UI only. Phase 2: point the forms at Laravel's
 * Password::sendResetLink() / Password::reset() and keep the same Blade.
 */
class PasswordPageController extends Controller
{
    public function forgot(): View
    {
        return view('auth.forgot-password');
    }

    public function reset(Request $request, string $token = 'sample-token'): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }
}
