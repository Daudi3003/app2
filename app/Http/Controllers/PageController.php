<?php

namespace App\Http\Controllers;

use App\Support\LearnHubData;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Public marketing and catalogue pages.
 *
 * Phase 2: replace each LearnHubData call with the equivalent Eloquent query
 * (for example `Course::with('instructor.users')->paginate(12)`). The Blade
 * views already expect the column names used by the migrations.
 */
class PageController extends Controller
{
    public function home(): View
    {
        return view('home.index', [
            'featured'     => LearnHubData::featuredCourses(6),
            'categories'   => LearnHubData::categories(),
            'features'     => LearnHubData::features(),
            'stats'        => LearnHubData::platformStats(),
            'testimonials' => LearnHubData::testimonials()->take(3),
            'instructors'  => LearnHubData::instructors()->take(4),
            'searchIndex'  => LearnHubData::courseSearchIndex(),
        ]);
    }

    public function courses(Request $request): View
    {
        return view('courses.index', [
            'courses'    => LearnHubData::courses(),
            'categories' => LearnHubData::categories(),
            'levels'     => LearnHubData::levels(),
            'query'      => (string) $request->query('q', ''),
        ]);
    }

    public function courseShow(string $id): View
    {
        $course = LearnHubData::course($id);

        abort_if($course === null, 404);

        return view('courses.show', [
            'course'  => $course,
            'related' => LearnHubData::courses()
                ->where('category', $course->category)
                ->where('id', '!=', $course->id)
                ->take(3),
        ]);
    }

    public function instructors(): View
    {
        return view('instructors.index', [
            'instructors' => LearnHubData::instructors(),
        ]);
    }

    public function instructorShow(string $id): View
    {
        $instructor = LearnHubData::instructor($id);

        abort_if($instructor === null, 404);

        return view('instructors.show', [
            'instructor' => $instructor,
            'courses'    => LearnHubData::coursesByInstructor((int) $id),
        ]);
    }

    public function about(): View
    {
        return view('pages.about', [
            'stats'       => LearnHubData::platformStats(),
            'instructors' => LearnHubData::instructors()->take(4),
            'features'    => LearnHubData::features(),
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'faqs' => LearnHubData::faqs(),
        ]);
    }

    public function blog(): View
    {
        return view('pages.blog', [
            'posts' => LearnHubData::posts(),
        ]);
    }
}
