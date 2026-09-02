@extends('layouts.app')

@section('title', 'Blog')

@section('content')

<section class="page-head">
    <div class="container container--wide">
        <div class="page-head__inner">
            <x-breadcrumbs light :items="['Home' => route('home'), 'Blog' => null]" />
            <h1>The LearnHub Blog</h1>
            <p>Practical writing on learning, careers and the craft — from the instructors who teach here.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container container--wide">
        <div class="grid grid--3">
            @forelse ($posts as $post)
                <article class="post-card" data-reveal data-reveal-delay="{{ $loop->index * 60 }}">
                    <div class="post-card__media"><span aria-hidden="true">{{ $post->emoji }}</span></div>
                    <div class="post-card__body">
                        <div class="post-card__meta">
                            <span class="badge badge--primary">{{ $post->category }}</span>
                            <span>{{ $post->read_time }}</span>
                        </div>
                        <h3><a href="#">{{ $post->title }}</a></h3>
                        <p class="t-clamp-3">{{ $post->excerpt }}</p>
                        <div class="post-card__foot row row--between">
                            <span class="t-xs t-muted">{{ $post->author }}</span>
                            <span class="t-xs t-muted">{{ $post->date }}</span>
                        </div>
                    </div>
                </article>
            @empty
                <x-empty-state emoji="📝" title="No articles yet"
                               text="New writing is published every fortnight. Check back soon." />
            @endforelse
        </div>

        <x-pagination :current="1" :last="3" />
    </div>
</section>

@endsection
