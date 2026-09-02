@extends('layouts.instructor')

@php $editing = $course !== null; @endphp

@section('title', $editing ? 'Edit Course' : 'Create a Course')
@section('page_title', $editing ? 'Edit Course' : 'Create Course')
@section('page_subtitle', $editing ? $course->course_name : 'Four steps to a published course')

@section('content')

<x-breadcrumbs :items="[
    'Dashboard' => route('instructor.dashboard'),
    'My Courses' => route('instructor.courses'),
    ($editing ? 'Edit' : 'Create') => null,
]" />

<div class="pane-head">
    <div>
        <h2>{{ $editing ? 'Edit course' : 'Create a new course' }} 🚀</h2>
        <p>Work through each step — you can save as a draft at any point.</p>
    </div>
</div>

<div class="card">
    <div class="card__body">

        <form method="POST" action="{{ $editing ? route('instructor.courses.update', $course->id) : route('instructor.courses.store') }}" data-wizard="real">
            @csrf
            @if($editing) @method('PUT') @endif

            {{-- ---------- STEPPER ---------- --}}
            <div class="stepper">
                @foreach ([
                    ['Basic Info', 'Title, category and course details'],
                    ['Curriculum', 'Sections and lessons'],
                    ['Settings',   'Requirements and outcomes'],
                    ['Publish',    'Review and go live'],
                ] as $i => [$label, $sub])
                    <div class="step {{ $i === 0 ? 'is-active' : '' }}" data-step>
                        <span class="step__num">{{ $i + 1 }}</span>
                        <span class="step__text">
                            <span class="step__label">{{ $label }}</span>
                            <span class="step__sub">{{ $sub }}</span>
                        </span>
                    </div>
                    @if (! $loop->last)
                        <span class="step__line"></span>
                    @endif
                @endforeach
            </div>

            {{-- ---------- STEP 1: BASIC INFO ---------- --}}
            <div class="step-panel is-active" data-step-panel>
                <h3>Basic information</h3>
                <p class="t-muted">Start with what a student sees before they enrol.</p>

                <div class="form-grid mt-6">
                    <div class="field is-full">
                        <label class="field__label" for="cTitle">Course title <span class="req">*</span></label>
                        <input id="cTitle" name="course_name" type="text" class="input" required
                               maxlength="120" data-count-target="titleCount"
                               placeholder="e.g. Complete Web Development Bootcamp"
                               value="{{ $editing ? $course->course_name : '' }}">
                        <span class="field__hint" id="titleCount"></span>
                    </div>

                    <div class="field is-full">
                        <label class="field__label" for="cSummary">Short summary <span class="req">*</span></label>
                        <input id="cSummary" name="summary" type="text" class="input" required maxlength="160"
                               placeholder="One sentence that sells the course"
                               value="{{ $editing ? $course->summary : '' }}">
                        <span class="field__hint">Appears under the title in search results.</span>
                    </div>

                    <div class="field is-full">
                        <label class="field__label" for="cDescription">Full description <span class="req">*</span></label>
                        <textarea id="cDescription" name="description" class="textarea" rows="7" required
                                  placeholder="What will students build? Who is this for? What makes it different?">{{ $editing ? $course->description : '' }}</textarea>
                    </div>

                    <div class="field">
                        <label class="field__label" for="cCategory">Category <span class="req">*</span></label>
                        <select id="cCategory" name="category" class="select" required>
                            <option value="">Choose a category…</option>
                            @foreach ($categories as $category)
                                <option {{ $editing && $course->category === $category->name ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label class="field__label" for="cLevel">Level <span class="req">*</span></label>
                        <select id="cLevel" name="level" class="select" required>
                            <option value="">Choose a level…</option>
                            @foreach ($levels as $level)
                                <option {{ $editing && $course->level === $level ? 'selected' : '' }}>{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label class="field__label" for="cDuration">Estimated duration <span class="req">*</span></label>
                        <input id="cDuration" name="duration" type="text" class="input" required
                               placeholder="e.g. 32 hours" value="{{ $editing ? $course->duration : '' }}">
                    </div>
<span class="field__hint">Enter 0 to publish the course for free.</span>
                    </div>

                    <div class="field is-full">
                        <label class="field__label">Course thumbnail</label>
                        <label class="dropzone" data-dropzone>
                            <span class="dropzone__icon" aria-hidden="true">🖼️</span>
                            <span class="dropzone__title">Drop an image here, or click to browse</span>
                            <span class="dropzone__hint">JPG or PNG, 1280 × 720 recommended, up to 4 MB</span>
                            <input type="file" accept="image/*">
                        </label>
                        <div data-file-list class="file-list"></div>
                    </div>
                </div>
            </div>

            {{-- ---------- STEP 2: CURRICULUM ---------- --}}
            <div class="step-panel" data-step-panel>
                <h3>Curriculum</h3>
                <p class="t-muted">Group your lessons into sections. You can reorder them later.</p>

                <div class="accordion mt-6" data-accordion="multi">
                    @foreach ([
                        ['Introduction', ['Welcome to the Course', 'What You Will Build', 'Setting Up Your Tools']],
                        ['Core Concepts', ['The Mental Model', 'Your First Example', 'Common Mistakes']],
                    ] as $si => [$sectionTitle, $lessons])
                        <div class="accordion__item {{ $si === 0 ? 'is-open' : '' }}">
                            <button type="button" class="accordion__trigger" data-accordion-trigger
                                    aria-expanded="{{ $si === 0 ? 'true' : 'false' }}">
                                <span>Section {{ $si + 1 }}: {{ $sectionTitle }}</span>
                                <span class="accordion__meta">{{ count($lessons) }} lessons</span>
                                <span class="accordion__chevron"><x-icon name="chevron-down" :size="18" /></span>
                            </button>

                            <div class="accordion__panel">
                                <div class="accordion__inner">
                                    @foreach ($lessons as $li => $lessonTitle)
                                        <div class="lesson-row">
                                            <span class="lesson-row__icon"><x-icon name="play-circle" :size="16" /></span>
                                            <span class="lesson-row__title">{{ $li + 1 }}. {{ $lessonTitle }}</span>
                                            <button type="button" class="btn-icon btn-icon--sm btn-icon--plain"
                                                    aria-label="Edit lesson"><x-icon name="edit" :size="14" /></button>
                                            <button type="button" class="btn-icon btn-icon--sm btn-icon--plain is-danger"
                                                    data-confirm-delete="{{ $lessonTitle }}"
                                                    aria-label="Delete lesson"><x-icon name="trash" :size="14" /></button>
                                        </div>
                                    @endforeach

                                    <button type="button" class="btn btn--ghost btn--sm mt-4"
                                            data-modal-open="addLessonModal">
                                        <x-icon name="plus" :size="15" /> Add a lesson to this section
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn--secondary mt-6"
                        data-toast="A new section was added" data-toast-type="success">
                    <x-icon name="plus" :size="16" /> Add Section
                </button>
            </div>

            {{-- ---------- STEP 3: SETTINGS ---------- --}}
            <div class="step-panel" data-step-panel>
                <h3>Requirements and outcomes</h3>
                <p class="t-muted">Tell students what they need beforehand and what they will walk away with.</p>

                <div class="form-grid mt-6">
                    <div class="field is-full">
                        <label class="field__label" for="cOutcomes">What students will learn <span class="req">*</span></label>
                        <textarea id="cOutcomes" class="textarea" rows="6" required
                                  placeholder="One outcome per line">{{ $editing ? $course->what_you_learn->implode("\n") : '' }}</textarea>
                        <span class="field__hint">One outcome per line — four to eight works best.</span>
                    </div>

                    <div class="field is-full">
                        <label class="field__label" for="cRequirements">Requirements</label>
                        <textarea id="cRequirements" class="textarea" rows="4"
                                  placeholder="One requirement per line">{{ $editing ? $course->requirements->implode("\n") : '' }}</textarea>
                    </div>

                    <div class="field">
                        <label class="field__label" for="cLanguage">Language</label>
                        <select id="cLanguage" class="select">
                            <option>English</option>
                            <option>Kiswahili</option>
                            <option>French</option>
                        </select>
                    </div>

                    <div class="field">
                        <label class="field__label" for="cCertificate">Certificate</label>
                        <select id="cCertificate" class="select">
                            <option>Issue a certificate on completion</option>
                            <option>No certificate</option>
                        </select>
                    </div>
                </div>

                <div class="switch-list mt-6">
                    <label class="switch">
                        <span class="switch__text">
                            <span class="switch__title">Allow Q&amp;A</span>
                            <span class="switch__sub">Students can ask questions on each lesson.</span>
                        </span>
                        <input type="checkbox" checked><span class="switch__track"></span>
                    </label>
                    <label class="switch">
                        <span class="switch__text">
                            <span class="switch__title">Allow reviews</span>
                            <span class="switch__sub">Enrolled students can rate and review the course.</span>
                        </span>
                        <input type="checkbox" checked><span class="switch__track"></span>
                    </label>
                    <label class="switch">
                        <span class="switch__text">
                            <span class="switch__title">Free preview lessons</span>
                            <span class="switch__sub">Let visitors watch the first two lessons before enrolling.</span>
                        </span>
                        <input type="checkbox" checked><span class="switch__track"></span>
                    </label>
                </div>
            </div>

            {{-- ---------- STEP 4: PUBLISH ---------- --}}
            <div class="step-panel" data-step-panel>
                <h3>Review and publish</h3>
                <p class="t-muted">Check everything below, then submit for review.</p>

                <x-alert type="info" :dismissible="false">
                    Courses are reviewed by the LearnHub academic team, usually within two working days.
                    You will be notified as soon as yours is approved.
                </x-alert>

                <div class="grid grid--2 mt-6">
                    <div class="card card--pad-sm">
                        <h4>Checklist</h4>
                        <ul class="learn-list" style="grid-template-columns:1fr">
                            <li><x-icon name="check" :size="16" /> <span>Title and description complete</span></li>
                            <li><x-icon name="check" :size="16" /> <span>Category and level chosen</span></li>
                            <li><x-icon name="check" :size="16" /> <span>At least two sections added</span></li>
                            <li><x-icon name="check" :size="16" /> <span>Learning outcomes written</span></li>
                            <li><x-icon name="alert" :size="16" /> <span>Thumbnail not yet uploaded</span></li>
                        </ul>
                    </div>

                    <div class="card card--pad-sm">
                        <h4>Visibility</h4>
                        <div class="check-list mt-4">
                            <label class="check">
                                <input type="radio" name="visibility" checked>
                                <span>Publish publicly
                                    <span class="check__sub">Listed in the catalogue and search.</span></span>
                            </label>
                            <label class="check">
                                <input type="radio" name="visibility">
                                <span>Unlisted
                                    <span class="check__sub">Reachable by direct link only.</span></span>
                            </label>
                            <label class="check">
                                <input type="radio" name="visibility">
                                <span>Keep as a draft
                                    <span class="check__sub">Visible only to you.</span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ---------- ACTIONS ---------- --}}
            <div class="form-actions">
                <button type="button" class="btn btn--secondary" data-wizard-back hidden>
                    <x-icon name="arrow-left" :size="16" /> Back
                </button>

                <div class="row" style="gap:var(--sp-3)">
                    <button type="button" class="btn btn--ghost"
                            data-toast="Draft saved" data-toast-type="success">
                        Save as Draft
                    </button>

                    <button type="button" class="btn btn--primary" data-wizard-next>
                        Continue <x-icon name="arrow-right" :size="16" class="icon icon--shift" />
                    </button>

                    <button type="submit" class="btn btn--success" data-wizard-submit hidden>
                        <x-icon name="check" :size="16" />
                        {{ $editing ? 'Save Changes' : 'Submit for Review' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ---------- ADD LESSON MODAL ---------- --}}
<div class="modal" id="addLessonModal" role="dialog" aria-modal="true"
     aria-labelledby="addLessonTitle" aria-hidden="true">
    <div class="modal__dialog">
        <div class="modal__head">
            <div>
                <h3 id="addLessonTitle">Add a lesson</h3>
                <p>Lessons appear in the order you create them.</p>
            </div>
            <button type="button" class="btn-icon btn-icon--sm btn-icon--plain"
                    data-modal-close aria-label="Close dialog"><x-icon name="x" :size="18" /></button>
        </div>

        <form data-simulate-form="Lesson added to the section ✓">
            <div class="modal__body">
                <div class="form">
                    <div class="field">
                        <label class="field__label" for="lTitle">Lesson title <span class="req">*</span></label>
                        <input id="lTitle" type="text" class="input" required placeholder="e.g. Flexbox in Practice">
                    </div>
                    <div class="field">
                        <label class="field__label" for="lDescription">Description</label>
                        <textarea id="lDescription" class="textarea" rows="3"
                                  placeholder="What does this lesson cover?"></textarea>
                    </div>
                    <div class="form-grid">
                        <div class="field">
                            <label class="field__label" for="lOrder">Lesson order</label>
                            <input id="lOrder" type="number" class="input" min="1" value="4">
                        </div>
                        <div class="field">
                            <label class="field__label" for="lDuration">Duration</label>
                            <input id="lDuration" type="text" class="input" placeholder="e.g. 24:18">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal__foot">
                <button type="button" class="btn btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn--primary">Add Lesson</button>
            </div>
        </form>
    </div>
</div>

@endsection
