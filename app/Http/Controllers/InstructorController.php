<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        return view(
            'instructor.dashboard',
            compact('user')
        );
    }
}