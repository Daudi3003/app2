<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Support\LearnHubData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentPortalController extends Controller
{
    public function dashboard(): View
    {
        $enrolled = LearnHubData::enrolledCourses();
        return view('student.dashboard', [
            'student' => LearnHubData::student(), 'stats' => LearnHubData::studentStats(),
            'continuing' => $enrolled->whereIn('status', ['in_progress', 'active', 'not_started'])->take(3),
            'assignments' => LearnHubData::studentAssignments()->whereIn('status', ['pending', 'overdue'])->take(4),
            'activity' => LearnHubData::studentActivity(), 'notifications' => LearnHubData::notifications()->take(4),
            'recommended' => LearnHubData::courses()->where('status', 'published')->reject(fn ($c) => $enrolled->pluck('id')->contains($c->id))->take(3),
        ]);
    }

    public function courses(): View
    {
        return view('student.courses', ['student' => LearnHubData::student(), 'courses' => LearnHubData::enrolledCourses()]);
    }

    public function course(string $id): View
    {
        $course = LearnHubData::course($id);
        abort_if($course === null, 404);
        $enrolment = LearnHubData::enrolledCourses()->firstWhere('id', (int) $id);
        return view('student.course', ['student' => LearnHubData::student(), 'course' => $course, 'enrolment' => $enrolment]);
    }

    public function lesson(string $id): View
    {
        $lesson = Lesson::with(['course.instructor.users', 'course.lessons'])->findOrFail((int) $id);
        $course = LearnHubData::course($lesson->course_id);
        $flat = $course?->lessons ?? collect();
        $index = $flat->search(fn ($item) => (int) $item->id === (int) $id);
        $index = $index === false ? 0 : $index;
        return view('student.lesson', [
            'student' => LearnHubData::student(), 'course' => $course, 'lesson' => $flat[$index] ?? null,
            'previous' => $flat[$index - 1] ?? null, 'next' => $flat[$index + 1] ?? null,
            'position' => $index + 1, 'total' => $flat->count(),
        ]);
    }

    public function assignments(): View { return view('student.assignments', ['student' => LearnHubData::student(), 'assignments' => LearnHubData::studentAssignments()]); }
    public function certificates(): View { return view('student.certificates', ['student' => LearnHubData::student(), 'certificates' => LearnHubData::certificates()]); }
    public function bookmarks(): View { return view('student.bookmarks', ['student' => LearnHubData::student(), 'courses' => collect()]); }
    public function messages(): View { return view('student.messages', ['student' => LearnHubData::student(), 'threads' => LearnHubData::messageThreads(), 'conversation' => LearnHubData::conversation()]); }
    public function notifications(): View { return view('student.notifications', ['student' => LearnHubData::student(), 'notifications' => LearnHubData::notifications()]); }
    public function profile(): View { return view('student.profile', ['student' => LearnHubData::student(), 'stats' => LearnHubData::studentStats(), 'certificates' => LearnHubData::certificates(), 'courses' => LearnHubData::enrolledCourses()->take(4)]); }
    public function settings(): View { return view('student.settings', ['student' => LearnHubData::student()]); }

    public function updateProfile(Request $request)
    {
        $user = Auth::user(); $student = $user->student; abort_unless($student, 403);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'digits:10', Rule::unique('students', 'phone')->ignore($student->id)],
        ]);
        $user->update(['name' => $data['name'], 'email' => $data['email']]);
        $student->update(['phone' => $data['phone']]);
        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['current_password' => ['required'], 'password' => ['required', 'min:8', 'confirmed']]);
        $user = Auth::user();
        if (! Hash::check($request->current_password, $user->password)) return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password updated successfully.');
    }
}
