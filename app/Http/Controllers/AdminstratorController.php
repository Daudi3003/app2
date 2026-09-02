<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminstratorController extends Controller
{
    public function dashboard()
    {
        return redirect()->route('admin.dashboard');
    }

    public function createInstructor()
    {
        return view('adminstrator.instructor.create');
    }

    public function storeInstructor(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'], 'specialization' => ['required', 'string', 'max:255'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create(['name' => $validated['name'], 'email' => $validated['email'], 'password' => Hash::make($validated['password']), 'usertype' => 'instructor']);
            Instructor::create(['user_id' => $user->id, 'phone' => $validated['phone'], 'specialization' => $validated['specialization']]);
        });

        return redirect()->route('admin.instructors')->with('success', 'Instructor created successfully.');
    }
}
