<?php

namespace App\Http\Controllers;

use App\Support\LearnHubData;
use Illuminate\View\View;

/**
 * Administrator portal screens.
 *
 * Note: the existing backend spells the model and route segment
 * "adminstrator". That spelling is preserved on the legacy routes; the new
 * admin UI is mounted at /admin/* and the legacy names remain registered as
 * aliases so nothing that already calls route('adminstrator.dashboard') breaks.
 */
class AdminPortalController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'admin'       => LearnHubData::administrator(),
            'stats'       => LearnHubData::adminStats(),
            'trend'       => LearnHubData::enrolmentTrend(),
            'breakdown'   => LearnHubData::categoryBreakdown(),
            'activity'    => LearnHubData::adminActivity(),
            'instructors' => LearnHubData::topInstructorsReport(),
            'enrollments' => LearnHubData::enrollments()->take(5),
        ]);
    }

    public function users(): View
    {
        return view('admin.users', [
            'admin' => LearnHubData::administrator(),
            'users' => LearnHubData::users(),
        ]);
    }

    public function students(): View
    {
        return view('admin.students', [
            'admin'    => LearnHubData::administrator(),
            'students' => LearnHubData::adminStudents(),
        ]);
    }

    public function instructors(): View
    {
        return view('admin.instructors', [
            'admin'       => LearnHubData::administrator(),
            'instructors' => LearnHubData::adminInstructors(),
        ]);
    }

    public function courses(): View
    {
        return view('admin.courses', [
            'admin'      => LearnHubData::administrator(),
            'courses'    => LearnHubData::adminCourses(),
            'categories' => LearnHubData::categories(),
        ]);
    }

    public function lessons(): View
    {
        return view('admin.lessons', [
            'admin'   => LearnHubData::administrator(),
            'lessons' => LearnHubData::adminLessons(),
        ]);
    }

    public function materials(): View
    {
        return view('admin.materials', [
            'admin'     => LearnHubData::administrator(),
            'materials' => LearnHubData::materials(),
        ]);
    }

    public function assignments(): View
    {
        return view('admin.assignments', [
            'admin'       => LearnHubData::administrator(),
            'assignments' => LearnHubData::adminAssignments(),
        ]);
    }

    public function enrollments(): View
    {
        return view('admin.enrollments', [
            'admin'       => LearnHubData::administrator(),
            'enrollments' => LearnHubData::enrollments(),
        ]);
    }

    public function reports(): View
    {
        return view('admin.reports', [
            'admin'       => LearnHubData::administrator(),
            'stats'       => LearnHubData::adminStats(),
            'trend'       => LearnHubData::enrolmentTrend(),
            'revenue'     => LearnHubData::revenueTrend(),
            'growth'      => LearnHubData::studentGrowth(),
            'breakdown'   => LearnHubData::categoryBreakdown(),
            'instructors' => LearnHubData::topInstructorsReport(),
            'reports'     => LearnHubData::reports(),
        ]);
    }

    public function settings(): View
    {
        return view('admin.settings', [
            'admin' => LearnHubData::administrator(),
        ]);
    }
}
