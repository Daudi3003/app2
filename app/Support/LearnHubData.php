<?php

namespace App\Support;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Material;
use App\Models\Report;
use App\Models\Student;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Database-backed adapter used by the existing LearnHub Blade UI.
 *
 * The original frontend was built around MockRecord objects.  We keep that
 * presentation shape so the existing UI can remain intact while all learner,
 * instructor, course, enrollment, assignment and administrator data comes
 * from MySQL.
 */
class LearnHubData
{
    protected static function records(iterable $rows): Collection
    {
        return collect($rows)->map(fn ($row) => $row instanceof MockRecord ? $row : new MockRecord($row));
    }

    protected static function initials(?string $name): string
    {
        return collect(preg_split('/\s+/', trim((string) $name)))
            ->filter()->take(2)->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))->implode('');
    }

    protected static function currentStudent(): ?Student
    {
        return Auth::check() ? Auth::user()->student()->with('user')->first() : null;
    }

    protected static function currentInstructor(): ?Instructor
    {
        return Auth::check() ? Auth::user()->instructor()->with('users')->first() : null;
    }

    protected static function currentAdmin(): ?\App\Models\Adminstrator
    {
        return Auth::check() ? Auth::user()->adminstrator()->with('users')->first() : null;
    }

    public static function categories(): Collection
    {
        return self::records(Course::query()->select('category')->whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category')->values()->map(fn ($name, $i) => [
            'id' => $i + 1, 'name' => $name, 'slug' => Str::slug($name), 'emoji' => '📚',
            'courses_count' => Course::where('category', $name)->count(), 'description' => 'Free courses on LearnHub.',
        ]));
    }

    public static function levels(): array
    {
        return ['Beginner', 'Intermediate', 'Advanced', 'All Levels'];
    }

    public static function instructors(): Collection
    {
        return self::records(Instructor::with('users')->withCount('courses')->get()->map(function ($i) {
            return [
                'id' => $i->id, 'user_id' => $i->user_id, 'name' => $i->users?->name ?? 'Instructor',
                'initials' => self::initials($i->users?->name), 'email' => $i->users?->email,
                'phone' => $i->phone, 'specialization' => $i->specialization, 'headline' => $i->specialization,
                'bio' => '', 'emoji' => '👨‍🏫', 'rating' => 0, 'reviews_count' => 0,
                'students_count' => Enrollment::whereIn('course_id', $i->courses()->pluck('id'))->distinct('student_id')->count('student_id'),
                'courses_count' => $i->courses_count, 'joined' => optional($i->created_at)->format('M Y'),
                'location' => 'Tanzania', 'expertise' => [$i->specialization],
            ];
        }));
    }

    public static function instructor(int|string $id): ?MockRecord
    {
        return self::instructors()->firstWhere('id', (int) $id);
    }

    protected static function mapCourse(Course $c, ?Student $student = null): MockRecord
    {
        $lessons = $c->lessons()->orderBy('lesson_order')->get();
        $enrolled = $student ? $c->students()->where('students.id', $student->id)->exists() : false;
        $completed = 0;
        $progress = 0;

        return new MockRecord([
            'id' => $c->id, 'course_name' => $c->course_name, 'title' => $c->course_name,
            'summary' => $c->summary ?: Str::limit($c->description, 150), 'description' => $c->description,
            'duration' => $c->duration, 'status' => $c->status, 'category' => $c->category ?: 'General',
            'level' => $c->level ?: 'All Levels', 'language' => 'English', 'emoji' => '📚',
            'rating' => 0, 'reviews_count' => 0, 'students_count' => $c->enrollments()->count(),
            'is_bestseller' => false, 'certificate' => true, 'enrolled' => $enrolled,
            'progress' => $progress, 'completed_lessons' => $completed, 'lessons_count' => $lessons->count(),
            'last_lesson' => $lessons->first()?->title, 'requirements' => [], 'what_you_learn' => [],
            'created_ts' => optional($c->created_at)->timestamp, 'updated_at_label' => optional($c->updated_at)->diffForHumans(),
            'instructor_name' => $c->instructor?->users?->name ?? 'Instructor',
            'instructor' => $c->instructor ? new MockRecord([
                'id' => $c->instructor->id, 'name' => $c->instructor->users?->name ?? 'Instructor',
                'initials' => self::initials($c->instructor->users?->name), 'specialization' => $c->instructor->specialization,
            ]) : null,
            'lessons' => self::records($lessons->map(fn ($l) => [
                'id' => $l->id, 'title' => $l->title, 'description' => $l->description,
                'content' => $l->content, 'lesson_order' => $l->lesson_order, 'duration' => '', 'preview' => false,
            ])),
            'curriculum' => self::records([['title' => 'Course Lessons', 'lessons_count' => $lessons->count(), 'lessons' => $lessons->map(fn ($l) => [
                'id' => $l->id, 'title' => $l->title, 'description' => $l->description, 'lesson_order' => $l->lesson_order, 'duration' => '', 'preview' => false,
            ])->values()->all()]]),
            'materials' => self::records($lessons->flatMap(fn ($l) => $l->material)->map(fn ($m) => [
                'id' => $m->id, 'title' => $m->title, 'material_type' => $m->material_type, 'file_url' => $m->file_url,
                'description' => $m->description,
            ])),
            'reviews' => collect(),
        ]);
    }

    public static function courses(): Collection
    {
        $query = Course::with(['instructor.users', 'lessons', 'enrollments'])->orderByDesc('created_at');
        if (request()->filled('q')) {
            $q = request('q');
            $query->where(fn ($builder) => $builder->where('course_name', 'like', "%{$q}%")->orWhere('description', 'like', "%{$q}%"));
        }
        return $query->get()->map(fn ($c) => self::mapCourse($c, self::currentStudent()));
    }

    public static function course(int|string $id): ?MockRecord
    {
        $course = Course::with(['instructor.users', 'lessons.material', 'enrollments'])->find((int) $id);
        return $course ? self::mapCourse($course, self::currentStudent()) : null;
    }

    public static function featuredCourses(int $limit = 6): Collection
    {
        return self::courses()->where('status', 'published')->take($limit)->values();
    }

    public static function coursesByInstructor(int $instructorId): Collection
    {
        return Course::with(['instructor.users', 'lessons', 'enrollments'])->where('instructor_id', $instructorId)->get()->map(fn ($c) => self::mapCourse($c, self::currentStudent()));
    }

    public static function courseSearchIndex(): string
    {
        return self::courses()->pluck('course_name')->implode('|');
    }

    public static function platformStats(): Collection
    {
        return self::records([
            ['label' => 'Free Courses', 'value' => Course::where('status', 'published')->count(), 'emoji' => '📚', 'tone' => 'primary'],
            ['label' => 'Students', 'value' => User::where('usertype', 'student')->count(), 'emoji' => '🎓', 'tone' => 'accent'],
            ['label' => 'Instructors', 'value' => User::where('usertype', 'instructor')->count(), 'emoji' => '👨‍🏫', 'tone' => 'success'],
            ['label' => 'Enrollments', 'value' => Enrollment::count(), 'emoji' => '🚀', 'tone' => 'info'],
        ]);
    }

    public static function features(): Collection { return self::records([]); }
    public static function testimonials(): Collection { return self::records([]); }
    public static function posts(): Collection { return self::records([]); }
    public static function faqs(): Collection { return self::records([]); }

    public static function student(): MockRecord
    {
        $s = self::currentStudent();
        $u = $s?->user;
        $name = $u?->name ?? Auth::user()?->name ?? 'Student';
        return new MockRecord([
            'id' => $s?->id, 'user_id' => $u?->id, 'name' => $name, 'first_name' => Str::before($name, ' '),
            'initials' => self::initials($name), 'email' => $u?->email, 'phone' => $s?->phone,
            'registration_no' => $s?->registration_no, 'bio' => '', 'joined' => optional($u?->created_at)->format('M Y'),
            'location' => 'Tanzania', 'learning_streak' => 0, 'hours_this_week' => 0,
        ]);
    }

    public static function studentStats(): Collection
    {
        $s = self::currentStudent();
        $enrolled = $s?->enrollments()->count() ?? 0;
        $assignments = $s ? Submission::where('student_id', $s->id)->count() : 0;
        return self::records([
            ['label' => 'Enrolled Courses', 'value' => $enrolled, 'emoji' => '📚', 'tone' => 'primary', 'delta' => '', 'direction' => 'up'],
            ['label' => 'Assignments', 'value' => $assignments, 'emoji' => '📝', 'tone' => 'accent', 'delta' => '', 'direction' => 'up'],
            ['label' => 'Certificates', 'value' => 0, 'emoji' => '🏆', 'tone' => 'success', 'delta' => '', 'direction' => 'up'],
            ['label' => 'Learning Hours', 'value' => 0, 'emoji' => '⏱️', 'tone' => 'info', 'delta' => '', 'direction' => 'up'],
        ]);
    }

    public static function enrolledCourses(): Collection
    {
        $s = self::currentStudent();
        if (!$s) return self::records([]);
        return $s->courses()->with(['instructor.users', 'lessons'])->get()->map(function ($c) use ($s) { $m = self::mapCourse($c, $s); $m->status = $c->pivot->status ?? 'active'; return $m; });
    }

    public static function studentAssignments(): Collection
    {
        $s = self::currentStudent();
        if (!$s) return self::records([]);
        return self::records(Assignment::with('courses')->whereHas('courses', fn ($q) => $q->whereHas('enrollments', fn ($e) => $e->where('student_id', $s->id)))->orderBy('due_date')->get()->map(function ($a) use ($s) {
            $submitted = Submission::where('assignment_id', $a->id)->where('student_id', $s->id)->exists();
            $status = $submitted ? 'submitted' : ($a->due_date && now()->startOfDay()->gt($a->due_date) ? 'overdue' : 'pending');
            return ['id' => $a->id, 'title' => $a->title, 'description' => $a->description, 'due_date' => optional($a->due_date)->format('d M Y'), 'course_name' => $a->courses?->course_name ?? '', 'instructor' => $a->courses?->instructor?->users?->name ?? '', 'status' => $status, 'emoji' => '📝', 'score' => null];
        }));
    }

    public static function certificates(): Collection { return self::records([]); }
    public static function notifications(): Collection { return self::records([]); }
    public static function studentActivity(): Collection { return self::records([]); }
    public static function messageThreads(): Collection { return self::records([]); }
    public static function conversation(): Collection { return self::records([]); }

    public static function instructorProfile(): MockRecord
    {
        $i = self::currentInstructor();
        $name = $i?->users?->name ?? Auth::user()?->name ?? 'Instructor';
        return new MockRecord(['id' => $i?->id, 'user_id' => $i?->user_id, 'name' => $name, 'initials' => self::initials($name), 'email' => $i?->users?->email, 'phone' => $i?->phone, 'specialization' => $i?->specialization, 'headline' => $i?->specialization, 'bio' => '', 'expertise' => [$i?->specialization], 'rating' => 0, 'reviews_count' => 0, 'joined' => optional($i?->created_at)->format('M Y'), 'location' => 'Tanzania']);
    }

    public static function instructorStats(): Collection
    {
        $i = self::currentInstructor();
        $courseIds = $i ? $i->courses()->pluck('id') : collect();
        return self::records([
            ['label' => 'Courses', 'value' => $courseIds->count(), 'emoji' => '📚', 'tone' => 'primary', 'delta' => '', 'direction' => 'up'],
            ['label' => 'Students', 'value' => Enrollment::whereIn('course_id', $courseIds)->distinct('student_id')->count('student_id'), 'emoji' => '🎓', 'tone' => 'accent', 'delta' => '', 'direction' => 'up'],
            ['label' => 'Assignments', 'value' => Assignment::whereIn('course_id', $courseIds)->count(), 'emoji' => '📝', 'tone' => 'warning', 'delta' => '', 'direction' => 'up'],
            ['label' => 'Revenue', 'value' => 'Free', 'emoji' => '🆓', 'tone' => 'success', 'delta' => '', 'direction' => 'up'],
        ]);
    }

    public static function instructorCourses(): Collection
    {
        $i = self::currentInstructor();
        return $i ? self::coursesByInstructor($i->id) : self::records([]);
    }

    public static function instructorStudents(): Collection
    {
        $i = self::currentInstructor(); if (!$i) return self::records([]);
        return self::records(Student::with('user')->whereHas('enrollments.course', fn ($q) => $q->where('instructor_id', $i->id))->get()->map(fn ($s) => ['id' => $s->id, 'name' => $s->user?->name, 'email' => $s->user?->email, 'registration_no' => $s->registration_no, 'phone' => $s->phone, 'initials' => self::initials($s->user?->name)]));
    }

    public static function lessons(): Collection
    {
        $i = self::currentInstructor(); if (!$i) return self::records([]);
        return self::records(Lesson::with('courses')->whereHas('courses', fn ($q) => $q->where('instructor_id', $i->id))->orderBy('course_id')->orderBy('lesson_order')->get()->map(fn ($l) => ['id' => $l->id, 'title' => $l->title, 'description' => $l->description, 'content' => $l->content, 'lesson_order' => $l->lesson_order, 'course_name' => $l->courses?->course_name]));
    }

    public static function materials(): Collection
    {
        $i = self::currentInstructor(); if (!$i) return self::records([]);
        return self::records(Material::with('lesson.courses')->where('instructor_id', $i->id)->get()->map(fn ($m) => ['id' => $m->id, 'title' => $m->title, 'material_type' => $m->material_type, 'file_url' => $m->file_url, 'description' => $m->description, 'lesson_title' => $m->lesson?->title]));
    }

    public static function instructorAssignments(): Collection
    {
        $i = self::currentInstructor(); if (!$i) return self::records([]);
        return self::records(Assignment::with('courses')->where('instructor_id', $i->id)->get()->map(fn ($a) => ['id' => $a->id, 'title' => $a->title, 'description' => $a->description, 'due_date' => optional($a->due_date)->format('d M Y'), 'course_name' => $a->courses?->course_name, 'max_score' => $a->max_score]));
    }

    public static function submissions(): Collection
    {
        $i = self::currentInstructor(); if (!$i) return self::records([]);
        return self::records(Submission::with(['assignments.courses', 'students.user'])->whereHas('assignments', fn ($q) => $q->where('instructor_id', $i->id))->get()->map(fn ($s) => ['id' => $s->id, 'student_name' => $s->students?->user?->name, 'assignment_title' => $s->assignments?->title, 'course_name' => $s->assignments?->courses?->course_name, 'submitted_at' => optional($s->submitted_at)->format('d M Y H:i'), 'score' => $s->score, 'status' => $s->status]));
    }

    public static function enrollments(): Collection
    {
        return self::records(Enrollment::with(['courses.instructor.users', 'students.user'])->latest()->get()->map(fn ($e) => ['id' => $e->id, 'student_name' => $e->students?->user?->name, 'course_name' => $e->courses?->course_name, 'instructor_name' => $e->courses?->instructor?->users?->name, 'enrollment_date' => optional($e->enrollment_date)->format('d M Y'), 'status' => $e->status]));
    }

    public static function instructorEnrolmentChart(): Collection { return self::records([]); }
    public static function coursePerformance(): Collection { return self::records([]); }

    public static function administrator(): MockRecord
    {
        $a = self::currentAdmin(); $name = $a?->users?->name ?? Auth::user()?->name ?? 'Administrator';
        return new MockRecord(['id' => $a?->id, 'user_id' => $a?->user_id, 'name' => $name, 'initials' => self::initials($name), 'email' => $a?->users?->email, 'phone' => $a?->phone, 'role_label' => 'Administrator', 'joined' => optional($a?->created_at)->format('M Y')]);
    }

    public static function adminStats(): Collection
    {
        return self::records([
            ['label' => 'Students', 'value' => User::where('usertype', 'student')->count(), 'emoji' => '🎓', 'tone' => 'primary', 'delta' => '', 'direction' => 'up'],
            ['label' => 'Instructors', 'value' => User::where('usertype', 'instructor')->count(), 'emoji' => '👨‍🏫', 'tone' => 'accent', 'delta' => '', 'direction' => 'up'],
            ['label' => 'Courses', 'value' => Course::count(), 'emoji' => '📚', 'tone' => 'success', 'delta' => '', 'direction' => 'up'],
            ['label' => 'Enrollments', 'value' => Enrollment::count(), 'emoji' => '🚀', 'tone' => 'info', 'delta' => '', 'direction' => 'up'],
        ]);
    }

    public static function users(): Collection
    {
        return self::records(User::latest()->get()->map(fn ($u) => ['id' => $u->id, 'name' => $u->name, 'email' => $u->email, 'usertype' => $u->usertype, 'initials' => self::initials($u->name), 'joined' => optional($u->created_at)->format('d M Y'), 'last_login' => '—', 'status' => 'active']));
    }

    public static function adminStudents(): Collection { return self::records(Student::with('user')->latest()->get()->map(fn ($s) => ['id' => $s->id, 'name' => $s->user?->name, 'email' => $s->user?->email, 'phone' => $s->phone, 'registration_no' => $s->registration_no, 'initials' => self::initials($s->user?->name), 'status' => 'active'])); }
    public static function adminInstructors(): Collection { return self::instructors(); }
    public static function adminCourses(): Collection { return self::courses(); }
    public static function adminLessons(): Collection { return self::records(Lesson::with('courses')->latest()->get()->map(fn ($l) => ['id' => $l->id, 'title' => $l->title, 'course_name' => $l->courses?->course_name, 'lesson_order' => $l->lesson_order])); }
    public static function adminAssignments(): Collection { return self::records(Assignment::with('courses')->latest()->get()->map(fn ($a) => ['id' => $a->id, 'title' => $a->title, 'course_name' => $a->courses?->course_name, 'due_date' => optional($a->due_date)->format('d M Y'), 'max_score' => $a->max_score])); }
    public static function enrolmentTrend(): Collection { return self::records([]); }
    public static function revenueTrend(): Collection { return self::records([]); }
    public static function studentGrowth(): Collection { return self::records([]); }
    public static function categoryBreakdown(): Collection { return self::records([]); }
    public static function topInstructorsReport(): Collection { return self::instructors()->map(fn ($i) => new MockRecord(['name' => $i->name, 'initials' => $i->initials, 'courses' => $i->courses_count, 'students' => $i->students_count, 'rating' => 0, 'revenue' => 0, 'pct' => 0])); }
    public static function adminActivity(): Collection { return self::records([]); }
    public static function reports(): Collection { return self::records(Report::latest()->get()->map(fn ($r) => ['id' => $r->id, 'title' => $r->title, 'description' => $r->description, 'report_date' => optional($r->report_date)->format('d M Y'), 'type' => 'Report', 'emoji' => '📊'])); }
}
