<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function register()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'registration_no' => ['required', 'string', 'max:255', Rule::unique('students', 'registration_no')],
            'phone' => ['required', 'digits:10', Rule::unique('students', 'phone')],
            'password' => ['required', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'usertype' => 'student',
            ]);

            Student::create([
                'user_id' => $user->id,
                'registration_no' => $validated['registration_no'],
                'phone' => $validated['phone'],
            ]);
        });

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }
}
