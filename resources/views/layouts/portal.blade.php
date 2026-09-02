@extends('layouts.base')

@section('body')
    <div class="portal">

        @include('partials.sidebar')

        <div class="portal__main">
            @include('partials.topbar')

            <main id="main" class="portal__content">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Shared confirmation dialog used by every delete action in the portals. --}}
    <div class="modal" id="confirmDeleteModal" role="dialog" aria-modal="true"
         aria-labelledby="confirmDeleteTitle" aria-hidden="true">
        <div class="modal__dialog modal__dialog--sm">
            <div class="modal__head">
                <div>
                    <h3 id="confirmDeleteTitle">Confirm deletion</h3>
                    <p>This action cannot be undone.</p>
                </div>
                <button type="button" class="btn-icon btn-icon--sm btn-icon--plain"
                        data-modal-close aria-label="Close dialog">
                    <x-icon name="x" :size="18" />
                </button>
            </div>
            <div class="modal__body">
                <p class="mb-0">
                    Are you sure you want to delete
                    <strong data-confirm-label>this item</strong>?
                </p>
            </div>
            <div class="modal__foot">
                <button type="button" class="btn btn--secondary" data-modal-close>Cancel</button>
                <button type="button" class="btn btn--danger" data-confirm-accept>
                    <x-icon name="trash" :size="16" /> Delete
                </button>
            </div>
        </div>
    </div>
@endsection
