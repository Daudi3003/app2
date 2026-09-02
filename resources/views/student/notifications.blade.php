@extends('layouts.student')

@section('title', 'Notifications')
@section('page_title', 'Notifications')
@section('page_subtitle', 'Everything that needs your attention')

@section('content')

<div class="pane-head">
    <div>
        <h2>Notifications 🔔</h2>
        <p>{{ $notifications->where('unread', true)->count() }} unread of {{ $notifications->count() }}</p>
    </div>
    <div class="pane-head__actions">
        <button type="button" class="btn btn--secondary" data-mark-all-read>
            <x-icon name="check" :size="17" /> Mark All as Read
        </button>
        <a href="{{ route('student.settings') }}" class="btn btn--ghost">
            <x-icon name="settings" :size="17" /> Preferences
        </a>
    </div>
</div>

@if ($notifications->isEmpty())
    <div class="card">
        <x-empty-state emoji="🔔" title="No notifications"
                       text="When something needs your attention it will show up here." />
    </div>
@else
    <div class="card">
        <div class="card__body card__body--flush">
            <div class="list">
                @foreach ($notifications as $note)
                    <div class="list__item list__item--top {{ $note->unread ? 'is-unread' : '' }}">
                        <span class="list__icon list__icon--{{ $note->tone }}">{{ $note->emoji }}</span>

                        <div class="list__body">
                            <div class="list__title">{{ $note->title }}</div>
                            <div class="list__sub">{{ $note->text }}</div>
                            <div class="t-xs t-muted mt-4">{{ $note->time }}</div>
                        </div>

                        <div class="list__end">
                            @if ($note->unread)
                                <button type="button" class="btn btn--ghost btn--sm" data-mark-read>
                                    Mark read
                                </button>
                            @endif
                            <button type="button" class="btn-icon btn-icon--sm is-danger"
                                    data-toast="Notification dismissed" data-toast-type="info"
                                    aria-label="Dismiss notification">
                                <x-icon name="x" :size="15" />
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@endsection
