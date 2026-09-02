@extends('layouts.admin')

@section('title', 'Manage Users')
@section('page_title', 'Users')
@section('page_subtitle', 'Every account on the platform')

@section('content')

<div class="pane-head">
    <div>
        <h2>Users 👥</h2>
        <p>{{ $users->count() }} accounts · {{ $users->where('status', 'active')->count() }} active</p>
    </div>
    <div class="pane-head__actions">
        <button type="button" class="btn btn--secondary"
                data-toast="User list exported to CSV" data-toast-type="success">
            <x-icon name="download" :size="17" /> Export
        </button>
        <button type="button" class="btn btn--primary" data-modal-open="userModal">
            <x-icon name="user-plus" :size="17" /> Add User
        </button>
    </div>
</div>

<section class="stats-row">
    <x-stat-card label="All Users" :value="$users->count()" emoji="👥" />
    <x-stat-card label="Students" :value="$users->where('usertype', 'student')->count()" emoji="🎓" tone="info" />
    <x-stat-card label="Instructors" :value="$users->where('usertype', 'instructor')->count()" emoji="👨‍🏫" tone="accent" />
    <x-stat-card label="Administrators" :value="$users->where('usertype', 'administrator')->count()" emoji="🛠️" tone="warning" />
</section>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search by name or email…"
                       aria-label="Search users" data-table-search="#usersTable">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="userRole">Filter by role</label>
            <select id="userRole" class="select" style="width:auto"
                    data-row-filter="#usersTable" data-filter-key="usertype">
                <option value="">All roles</option>
                <option value="student">Students</option>
                <option value="instructor">Instructors</option>
                <option value="administrator">Administrators</option>
            </select>
        </div>

        {{-- Bulk-action bar appears when rows are selected --}}
        <div class="alert alert--info" data-bulk-bar hidden>
            <span class="alert__icon"><x-icon name="check-circle" :size="19" /></span>
            <div class="alert__body">
                <strong><span data-bulk-count>0</span></strong> user(s) selected.
            </div>
            <button type="button" class="btn btn--danger btn--sm"
                    data-toast="Selected users would be deactivated" data-toast-type="warning">
                Deactivate selected
            </button>
        </div>

        <div class="table-wrap">
            <table class="table" id="usersTable">
                <thead>
                    <tr>
                        <th scope="col" style="width:44px">
                            <label class="sr-only" for="selectAllUsers">Select all users</label>
                            <input id="selectAllUsers" type="checkbox" data-select-all="#usersTable">
                        </th>
                        <th scope="col">User</th>
                        <th scope="col">Role</th>
                        <th scope="col">Joined</th>
                        <th scope="col">Last login</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="text-align:right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr data-row data-usertype="{{ $user->usertype }}"
                            data-row-text="{{ $user->name }} {{ $user->email }} {{ $user->usertype }}">
                            <td>
                                <label class="sr-only" for="selUser{{ $user->id }}">Select {{ $user->name }}</label>
                                <input id="selUser{{ $user->id }}" type="checkbox" data-select-row>
                            </td>
                            <td>
                                <div class="table__user">
                                    <span class="avatar avatar--sm">{{ $user->initials }}</span>
                                    <span style="min-width:0">
                                        <span class="table__user-name">{{ $user->name }}</span><br>
                                        <span class="table__user-sub">{{ $user->email }}</span>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge--{{ $user->usertype === 'administrator' ? 'danger' : ($user->usertype === 'instructor' ? 'accent' : 'primary') }}">
                                    {{ ucfirst($user->usertype) }}
                                </span>
                            </td>
                            <td class="t-nowrap t-sm t-muted">{{ $user->joined }}</td>
                            <td class="t-nowrap t-sm t-muted">{{ $user->last_login }}</td>
                            <td><x-status-badge :status="$user->status" /></td>
                            <td>
                                <div class="table__actions">
                                    <button type="button" class="btn-icon btn-icon--sm"
                                            data-toast="User profile would open here" data-toast-type="info"
                                            aria-label="View {{ $user->name }}">
                                        <x-icon name="eye" :size="15" />
                                    </button>
                                    <button type="button" class="btn-icon btn-icon--sm"
                                            data-modal-open="userModal" aria-label="Edit {{ $user->name }}">
                                        <x-icon name="edit" :size="15" />
                                    </button>
                                    <button type="button" class="btn-icon btn-icon--sm is-danger"
                                            data-confirm-delete="{{ $user->name }}"
                                            aria-label="Delete {{ $user->name }}">
                                        <x-icon name="trash" :size="15" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div data-table-empty hidden>
            <x-empty-state emoji="👥" title="No users match your search"
                           text="Try a different keyword or clear the role filter." />
        </div>

        <x-pagination :current="1" :last="4" />
    </div>
</div>

{{-- ---------- ADD / EDIT USER MODAL ---------- --}}
<div class="modal" id="userModal" role="dialog" aria-modal="true"
     aria-labelledby="userModalTitle" aria-hidden="true">
    <div class="modal__dialog">
        <div class="modal__head">
            <div>
                <h3 id="userModalTitle">User details</h3>
                <p>Create or update a platform account.</p>
            </div>
            <button type="button" class="btn-icon btn-icon--sm btn-icon--plain"
                    data-modal-close aria-label="Close dialog"><x-icon name="x" :size="18" /></button>
        </div>

        <form data-simulate-form="User saved successfully ✓">
            <div class="modal__body">
                <div class="form">
                    <div class="form-grid">
                        <div class="field">
                            <label class="field__label" for="uName">Full name <span class="req">*</span></label>
                            <input id="uName" name="name" type="text" class="input" required>
                        </div>
                        <div class="field">
                            <label class="field__label" for="uEmail">Email <span class="req">*</span></label>
                            <input id="uEmail" name="email" type="email" class="input" required>
                        </div>
                        <div class="field">
                            <label class="field__label" for="uType">Role <span class="req">*</span></label>
                            <select id="uType" name="usertype" class="select" required>
                                <option value="">Choose a role…</option>
                                <option value="student">Student</option>
                                <option value="instructor">Instructor</option>
                                <option value="administrator">Administrator</option>
                            </select>
                        </div>
                        <div class="field">
                            <label class="field__label" for="uStatus">Status</label>
                            <select id="uStatus" class="select">
                                <option>Active</option>
                                <option>Inactive</option>
                                <option>Suspended</option>
                            </select>
                        </div>
                        <div class="field is-full">
                            <label class="field__label" for="uPassword">Temporary password <span class="req">*</span></label>
                            <div class="input-group input-group--trail">
                                <input id="uPassword" name="password" type="password" class="input" required>
                                <button type="button" class="input-group__action"
                                        data-password-toggle="uPassword" aria-label="Show password">
                                    <x-icon name="eye" :size="18" />
                                </button>
                            </div>
                            <span class="field__hint">The user is asked to change this on first login.</span>
                        </div>
                    </div>

                    <label class="check">
                        <input type="checkbox" checked>
                        <span>Email the user their login details</span>
                    </label>
                </div>
            </div>

            <div class="modal__foot">
                <button type="button" class="btn btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn--primary">Save User</button>
            </div>
        </form>
    </div>
</div>

@endsection
