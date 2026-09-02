<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $student = $user->student;
        return view('student.dashboard', compact('user', 'student'));
    }

    public function courses()
    {
        $courses = Course::with('instructor.users')->where('status', 'published')->latest()->get();
        return view('student.courses', compact('courses'));
    }

    public function enroll(Course $course)
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);

        Enrollment::firstOrCreate(
            ['student_id' => $student->id, 'course_id' => $course->id],
            ['enrollment_date' => now()->toDateString(), 'status' => 'active']
        );

        return back()->with('success', 'You have enrolled successfully.');
    }

    public function myCourses()
    {
        $student = Auth::user()->student;
        $courses = $student?->courses()->with('instructor.users')->get() ?? collect();
        return view('student.my-course', compact('courses'));
    }

    public function materials(Course $course)
    {
        $this->ensureEnrolled($course->id);
        $course->load('lessons.material');
        return view('student.materials', compact('course'));
    }

    public function assignments(Course $course)
    {
        $this->ensureEnrolled($course->id);
        $course->load('assignments');
        return view('student.assignments', compact('course'));
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $student = Auth::user()->student;
        abort_unless($student, 403);
        $this->ensureEnrolled($assignment->course_id);

        $validated = $request->validate(['file' => ['required', 'file', 'max:10240']]);
        $filePath = $validated['file']->store('submissions', 'public');

        Submission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => $student->id],
            ['submission_file' => $filePath, 'submitted_at' => now(), 'status' => 'submitted']
        );

        return back()->with('success', 'Assignment submitted successfully.');
    }

    protected function ensureEnrolled(int $courseId): void
    {
        $student = Auth::user()->student;
        abort_unless($student && Enrollment::where('student_id', $student->id)->where('course_id', $courseId)->exists(), 403);
    }
}
