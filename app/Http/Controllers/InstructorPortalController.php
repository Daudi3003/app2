<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Support\LearnHubData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InstructorPortalController extends Controller
{
    public function dashboard(): View
    {
        return view('instructor.dashboard', [
            'instructor' => LearnHubData::instructorProfile(), 'stats' => LearnHubData::instructorStats(),
            'courses' => LearnHubData::instructorCourses()->take(4), 'chart' => LearnHubData::instructorEnrolmentChart(),
            'performance' => LearnHubData::coursePerformance(), 'enrollments' => LearnHubData::enrollments()->take(5),
            'submissions' => LearnHubData::submissions()->take(4),
        ]);
    }

    public function courses(): View { return view('instructor.courses', ['instructor' => LearnHubData::instructorProfile(), 'courses' => LearnHubData::instructorCourses()]); }

    public function courseCreate(): View { return view('instructor.course-form', ['instructor' => LearnHubData::instructorProfile(), 'categories' => LearnHubData::categories(), 'levels' => LearnHubData::levels(), 'course' => null]); }

    public function courseEdit(string $id): View
    {
        $instructor = Auth::user()->instructor;
        $course = Course::where('id', $id)->where('instructor_id', $instructor?->id)->firstOrFail();
        return view('instructor.course-form', ['instructor' => LearnHubData::instructorProfile(), 'categories' => LearnHubData::categories(), 'levels' => LearnHubData::levels(), 'course' => LearnHubData::course($course->id)]);
    }

    public function courseStore(Request $request)
    {
        $instructor = Auth::user()->instructor;
        abort_unless($instructor, 403);
        $data = $this->validatedCourse($request);
        Course::create(array_merge($data, ['instructor_id' => $instructor->id, 'admin_id' => $this->adminId(), 'status' => 'published']));
        return redirect()->route('instructor.courses')->with('success', 'Course created successfully.');
    }

    public function courseUpdate(Request $request, string $id)
    {
        $instructor = Auth::user()->instructor;
        abort_unless($instructor, 403);
        $course = Course::where('id', $id)->where('instructor_id', $instructor->id)->firstOrFail();
        $course->update($this->validatedCourse($request));
        return redirect()->route('instructor.courses')->with('success', 'Course updated successfully.');
    }

    protected function validatedCourse(Request $request): array
    {
        return $request->validate([
            'course_name' => ['required', 'string', 'max:120'], 'summary' => ['nullable', 'string', 'max:160'],
            'description' => ['required', 'string'], 'category' => ['nullable', 'string', 'max:100'],
            'level' => ['nullable', 'string', 'max:50'], 'duration' => ['nullable', 'string', 'max:100'],
        ]);
    }

    protected function adminId(): ?int
    {
        return \App\Models\Adminstrator::query()->value('id');
    }

    public function lessons(): View { return view('instructor.lessons', ['instructor' => LearnHubData::instructorProfile(), 'lessons' => LearnHubData::lessons(), 'courses' => LearnHubData::instructorCourses()]); }
    public function materials(): View { return view('instructor.materials', ['instructor' => LearnHubData::instructorProfile(), 'materials' => LearnHubData::materials(), 'lessons' => LearnHubData::lessons()]); }
    public function assignments(): View { return view('instructor.assignments', ['instructor' => LearnHubData::instructorProfile(), 'assignments' => LearnHubData::instructorAssignments(), 'submissions' => LearnHubData::submissions(), 'courses' => LearnHubData::instructorCourses()]); }
    public function students(): View { return view('instructor.students', ['instructor' => LearnHubData::instructorProfile(), 'students' => LearnHubData::instructorStudents(), 'courses' => LearnHubData::instructorCourses()]); }
    public function enrollments(): View { return view('instructor.enrollments', ['instructor' => LearnHubData::instructorProfile(), 'enrollments' => LearnHubData::enrollments()->filter(fn ($e) => LearnHubData::instructorCourses()->pluck('id')->contains($e->course_id))->values()]); }
    public function messages(): View { return view('instructor.messages', ['instructor' => LearnHubData::instructorProfile(), 'threads' => LearnHubData::messageThreads(), 'conversation' => LearnHubData::conversation()]); }
    public function reports(): View { return view('instructor.reports', ['instructor' => LearnHubData::instructorProfile(), 'stats' => LearnHubData::instructorStats(), 'chart' => LearnHubData::instructorEnrolmentChart(), 'revenue' => LearnHubData::revenueTrend(), 'performance' => LearnHubData::coursePerformance()]); }
    public function profile(): View { return view('instructor.profile', ['instructor' => LearnHubData::instructorProfile(), 'stats' => LearnHubData::instructorStats(), 'courses' => LearnHubData::instructorCourses()->take(4)]); }
    public function settings(): View { return view('instructor.settings', ['instructor' => LearnHubData::instructorProfile()]); }
}
