@props(['course', 'showProgress' => false])

{{--
    The single course card used across the home page, catalogue, student
    portal and instructor screens. It reads the same attribute names an
    Eloquent Course model will expose after the database phase.
--}}

<article class="course-card {{ $showProgress ? 'course-card--progress' : '' }}"
         data-course-card
         data-title="{{ $course->course_name }}"
         data-category="{{ $course->category }}"
         data-level="{{ $course->level }}"
         data-rating="{{ $course->rating }}"
         data-students="{{ $course->students_count }}"
         data-instructor="{{ $course->instructor_name }}"
         data-date="{{ $course->created_ts }}">

    <div class="course-card__media">
        <span class="course-card__thumb" aria-hidden="true">{{ $course->emoji }}</span>

        <div class="course-card__badges">
            @if ($course->is_bestseller)
                <span class="badge">Bestseller</span>
            @endif
            <span class="badge badge--success">Free</span>
        </div>

        <button type="button" class="course-card__fav"
                data-favourite="course-{{ $course->id }}"
                aria-pressed="false" aria-label="Save {{ $course->course_name }} to bookmarks">
            <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor"
                 stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1L12 21l7.7-7.6 1.1-1a5.5 5.5 0 0 0 0-7.8z"/>
            </svg>
        </button>

        <span class="course-card__duration">
            <x-icon name="clock" :size="13" /> {{ $course->duration }}
        </span>
    </div>

    <div class="course-card__body">
        <span class="course-card__category">{{ $course->category }}</span>

        <h3 class="course-card__title">
            <a href="{{ route('courses.show', $course->id) }}">{{ $course->course_name }}</a>
        </h3>

        <div class="course-card__instructor">
            <span class="avatar avatar--xs">{{ $course->instructor?->initials }}</span>
            {{ $course->instructor_name }}
        </div>

        @if ($showProgress)
            <div class="course-card__progress">
                <x-progress :value="$course->progress"
                            :label="$course->progress >= 100 ? 'Completed' : 'Progress'"
                            :tone="$course->progress >= 100 ? 'success' : ''" />
                @if ($course->last_lesson)
                    <p class="t-xs t-muted mt-4 mb-0 t-clamp-2">Last lesson: {{ $course->last_lesson }}</p>
                @endif
            </div>

            <div class="course-card__foot">
                <x-status-badge :status="$course->status" />
                <a href="{{ route('student.course', $course->id) }}" class="btn btn--primary btn--sm">
                    {{ $course->progress >= 100 ? 'Review' : ($course->progress > 0 ? 'Continue' : 'Start') }}
                    <x-icon name="arrow-right" :size="15" class="icon icon--shift" />
                </a>
            </div>
        @else
            <div class="course-card__stats">
                <x-rating :score="$course->rating" :count="$course->reviews_count" />
                <span><x-icon name="users" :size="13" /> {{ number_format($course->students_count) }}</span>
            </div>

            <div class="course-card__foot">
                <div class="course-card__price">
                    <strong class="is-free">FREE 🎓</strong>
                </div>
<a href="{{ route('courses.show', $course->id) }}" class="btn btn--secondary btn--sm">
                    View Course
                </a>
            </div>
        @endif
    </div>
</article>
