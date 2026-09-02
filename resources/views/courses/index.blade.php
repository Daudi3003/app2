@extends('layouts.app')

@section('title', 'All Courses')
@section('meta_description', 'Browse over a thousand expert-led online courses on LearnHub.')

@section('content')

<section class="page-head">
    <div class="container container--wide">
        <div class="page-head__inner">
            <x-breadcrumbs light :items="['Home' => route('home'), 'Courses' => null]" />
            <h1>Explore Courses</h1>
            <p>Find the right course for where you are now — filter by category, level and rating.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container container--wide">

        <div class="catalog" data-catalog>

            {{-- ---------- FILTERS ---------- --}}
            <aside class="catalog__aside">
                <button type="button" class="btn btn--secondary btn--block filters-toggle mb-4"
                        data-filters-toggle aria-expanded="false">
                    <x-icon name="sliders" :size="17" /> Filters &amp; Sorting
                </button>

                <div class="card" data-filters-panel>
                    <div class="filters">

                        <div class="filters__group">
                            <label class="field__label" for="catalogSearch">Search</label>
                            <div class="search" style="margin-top:8px">
                                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                                <input id="catalogSearch" type="search" class="search__input"
                                       placeholder="Course or instructor…"
                                       value="{{ $query }}" data-catalog-search>
                                <button type="button" class="search__clear" aria-label="Clear search">
                                    <x-icon name="x" :size="15" />
                                </button>
                            </div>
                        </div>

                        <fieldset class="filters__group" style="border:0;margin:0;padding:var(--sp-5)">
                            <legend class="filters__title">Category</legend>
                            <div class="check-list">
                                @foreach ($categories as $category)
                                    <label class="check">
                                        <input type="checkbox" value="{{ $category->name }}" data-filter="category">
                                        <span>{{ $category->emoji }} {{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>

                        <fieldset class="filters__group" style="border:0;margin:0;padding:var(--sp-5)">
                            <legend class="filters__title">Level</legend>
                            <div class="check-list">
                                @foreach ($levels as $level)
                                    <label class="check">
                                        <input type="checkbox" value="{{ $level }}" data-filter="level">
                                        <span>{{ $level }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>



                        <fieldset class="filters__group" style="border:0;margin:0;padding:var(--sp-5)">
                            <legend class="filters__title">Rating</legend>
                            <div class="check-list">
                                <label class="check"><input type="checkbox" value="4.5" data-filter="rating"><span>4.5 &amp; up ⭐</span></label>
                                <label class="check"><input type="checkbox" value="4.0" data-filter="rating"><span>4.0 &amp; up ⭐</span></label>
                            </div>
                        </fieldset>

                        <div class="filters__group">
                            <button type="button" class="btn btn--ghost btn--block" data-catalog-reset>
                                <x-icon name="refresh" :size="16" /> Clear all filters
                            </button>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- ---------- RESULTS ---------- --}}
            <div>
                <div class="catalog__bar">
                    <p class="catalog__count mb-0">
                        <strong data-catalog-count>{{ $courses->count() }}</strong> courses found
                    </p>

                    <span class="spacer"></span>

                    <label class="sr-only" for="catalogSort">Sort courses by</label>
                    <select id="catalogSort" class="select" data-catalog-sort style="width:auto">
                        <option value="popular">Most popular</option>
                        <option value="newest">Newest</option>
                        <option value="rating">Highest rated</option>
                                                                        <option value="title">Title A–Z</option>
                    </select>

                    <div class="view-toggle" role="group" aria-label="Change layout">
                        <button type="button" data-view="grid" class="is-active" aria-pressed="true" aria-label="Grid view">
                            <x-icon name="grid" :size="16" />
                        </button>
                        <button type="button" data-view="list" aria-pressed="false" aria-label="List view">
                            <x-icon name="list" :size="16" />
                        </button>
                    </div>
                </div>

                <div class="courses-grid" data-catalog-grid>
                    @foreach ($courses as $course)
                        <x-course-card :course="$course" />
                    @endforeach
                </div>

                <div data-catalog-empty hidden>
                    <x-empty-state
                        emoji="🔍"
                        title="No courses found"
                        text="Try changing your search or filters — or browse the full catalogue.">
                        <button type="button" class="btn btn--primary" data-catalog-reset>
                            Browse All Courses
                        </button>
                    </x-empty-state>
                </div>

                <x-pagination :current="1" :last="4" />
            </div>
        </div>
    </div>
</section>

@endsection
