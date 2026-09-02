<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = Auth::user();

        return match ($user->usertype) {
            'student' => redirect()->intended(route('student.dashboard')),
            'instructor' => redirect()->intended(route('instructor.dashboard')),
            'administrator' => redirect()->intended(route('admin.dashboard')),
            default => tap(redirect()->route('home'), fn () => Auth::logout()),
        };
    }
}
