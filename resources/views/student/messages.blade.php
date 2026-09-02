@extends('layouts.student')

@section('title', 'Messages')
@section('page_title', 'Messages')
@section('page_subtitle', 'Conversations with your instructors')

@section('content')

<div class="messages">

    <div class="messages__list" data-thread-list>
        <div style="padding:var(--sp-4);border-bottom:1px solid var(--border)">
            <div class="search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search messages…"
                       aria-label="Search messages">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>
        </div>

        @foreach ($threads as $thread)
            <div class="thread-item {{ $loop->first ? 'is-active' : '' }} {{ $thread->unread ? 'is-unread' : '' }}"
                 data-thread
                 data-thread-name="{{ $thread->name }}"
                 data-thread-initials="{{ $thread->initials }}"
                 role="button" tabindex="0">
                <span class="avatar avatar--sm">{{ $thread->initials }}</span>
                <div class="thread-item__body">
                    <div class="thread-item__name">{{ $thread->name }}</div>
                    <div class="thread-item__snippet">{{ $thread->snippet }}</div>
                </div>
                <span class="t-xs t-muted t-nowrap">{{ $thread->time }}</span>
            </div>
        @endforeach
    </div>

    <div class="messages__thread">
        <div class="messages__head">
            <span class="avatar avatar--sm" data-thread-avatar>{{ $threads->first()->initials }}</span>
            <div style="flex:1;min-width:0">
                <strong data-thread-name>{{ $threads->first()->name }}</strong>
                <div class="t-xs t-muted">Instructor · usually replies within a day</div>
            </div>
            <button type="button" class="btn-icon" aria-label="Conversation options">
                <x-icon name="more" :size="18" />
            </button>
        </div>

        <div class="messages__body" data-message-body>
            @foreach ($conversation as $message)
                <div class="bubble bubble--{{ $message->direction }}">
                    {{ $message->text }}
                    <span class="bubble__time">{{ $message->time }}</span>
                </div>
            @endforeach
        </div>

        <form class="messages__compose" data-message-form>
            <label class="sr-only" for="messageInput">Write a message</label>
            <input id="messageInput" type="text" class="input" placeholder="Write a message…" autocomplete="off">
            <button type="submit" class="btn btn--primary" aria-label="Send message">
                <x-icon name="send" :size="17" />
            </button>
        </form>
    </div>
</div>

@endsection
