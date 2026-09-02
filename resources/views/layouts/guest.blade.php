@extends('layouts.base')

@section('body')
    <div class="auth">

        <aside class="auth__aside">
            <x-brand light />

            <div>
                <h2>@yield('aside_title', 'Learn new skills. Advance your future.')</h2>
                <p>@yield('aside_text', 'Join more than twenty thousand learners building real, job-ready skills with LearnHub.')</p>

                <ul class="auth__points">
                    <li><x-icon name="check-circle" :size="19" /><span>Over a thousand expert-led courses</span></li>
                    <li><x-icon name="check-circle" :size="19" /><span>Lifetime access with every future update</span></li>
                    <li><x-icon name="check-circle" :size="19" /><span>Verifiable certificates on completion</span></li>
                    <li><x-icon name="check-circle" :size="19" /><span>Learn on any device, at your own pace</span></li>
                </ul>
            </div>

            <div class="auth__quote">
                <p>“I enrolled with no background in tech at all. Eleven months later I am employed as a developer.”</p>
                <div class="row row--nowrap">
                    <span class="avatar avatar--sm">AH</span>
                    <span>
                        <strong style="color:#fff">Amina Hassan</strong><br>
                        <small style="color:#94a3b8">Junior Developer at Kipaji Tech</small>
                    </span>
                </div>
            </div>
        </aside>

        <main id="main" class="auth__main">
            <div class="auth__card">
                <x-brand />
                @yield('content')
            </div>
        </main>
    </div>
@endsection
