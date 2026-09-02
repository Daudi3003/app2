@extends('layouts.admin')

@section('title', 'Manage Materials')
@section('page_title', 'Materials')
@section('page_subtitle', 'Files and resources across the platform')

@section('content')

<div class="pane-head">
    <div>
        <h2>Materials 📁</h2>
        <p>{{ $materials->count() }} files · {{ number_format($materials->sum('downloads')) }} downloads</p>
    </div>
    <div class="pane-head__actions">
        <button type="button" class="btn btn--secondary"
                data-toast="Storage report generated" data-toast-type="success">
            <x-icon name="bar-chart" :size="17" /> Storage Report
        </button>
    </div>
</div>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search materials…"
                       aria-label="Search materials" data-table-search="#adminMaterials">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="adMatType">Filter by type</label>
            <select id="adMatType" class="select" style="width:auto"
                    data-row-filter="#adminMaterials" data-filter-key="type">
                <option value="">All types</option>
                <option value="PDF">PDF</option>
                <option value="Video">Video</option>
                <option value="Document">Document</option>
                <option value="Archive">Archive</option>
                <option value="Link">Link</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="table" id="adminMaterials">
                <thead>
                    <tr>
                        <th scope="col">Material</th>
                        <th scope="col">Course</th>
                        <th scope="col">Lesson</th>
                        <th scope="col">Type</th>
                        <th scope="col">Uploaded</th>
                        <th scope="col" class="is-numeric">Downloads</th>
                        <th scope="col" style="text-align:right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($materials as $material)
                        <tr data-row data-type="{{ $material->material_type }}"
                            data-row-text="{{ $material->title }} {{ $material->course_name }} {{ $material->material_type }}">
                            <td>
                                <div class="table__user">
                                    <span class="file-row__icon">{{ $material->emoji }}</span>
                                    <span style="min-width:0">
                                        <span class="table__user-name t-clamp-2">{{ $material->title }}</span><br>
                                        <span class="table__user-sub">{{ $material->size }}</span>
                                    </span>
                                </div>
                            </td>
                            <td class="t-sm t-clamp-2">{{ $material->course_name }}</td>
                            <td class="t-sm t-clamp-2">{{ $material->lesson_title }}</td>
                            <td><span class="badge badge--primary">{{ $material->material_type }}</span></td>
                            <td class="t-nowrap t-sm t-muted">{{ $material->uploaded_at }}</td>
                            <td class="is-numeric">{{ number_format($material->downloads) }}</td>
                            <td>
                                <div class="table__actions">
                                    <button type="button" class="btn-icon btn-icon--sm"
                                            data-toast="Download would start here" data-toast-type="info"
                                            aria-label="Download {{ $material->title }}">
                                        <x-icon name="download" :size="15" />
                                    </button>
                                    <button type="button" class="btn-icon btn-icon--sm is-danger"
                                            data-confirm-delete="{{ $material->title }}"
                                            aria-label="Delete {{ $material->title }}">
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
            <x-empty-state emoji="📁" title="No materials match your search"
                           text="Try a different keyword or clear the type filter." />
        </div>
    </div>
</div>

@endsection
