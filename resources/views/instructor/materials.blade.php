@extends('layouts.instructor')

@section('title', 'Course Materials')
@section('page_title', 'Materials')
@section('page_subtitle', 'PDFs, videos, documents and links')

@section('content')

<div class="pane-head">
    <div>
        <h2>Materials 📁</h2>
        <p>{{ $materials->count() }} files · {{ number_format($materials->sum('downloads')) }} total downloads</p>
    </div>
    <div class="pane-head__actions">
        <button type="button" class="btn btn--primary" data-modal-open="uploadModal">
            <x-icon name="upload" :size="17" /> Upload Material
        </button>
    </div>
</div>

<section class="stats-row">
    <x-stat-card label="PDFs" :value="$materials->where('material_type', 'PDF')->count()" emoji="📕" />
    <x-stat-card label="Videos" :value="$materials->where('material_type', 'Video')->count()" emoji="🎬" tone="info" />
    <x-stat-card label="Documents" :value="$materials->where('material_type', 'Document')->count()" emoji="📄" tone="warning" />
    <x-stat-card label="Links" :value="$materials->where('material_type', 'Link')->count()" emoji="🔗" tone="accent" />
</section>

<div class="card">
    <div class="card__body">
        <div class="toolbar">
            <div class="search toolbar__search">
                <span class="search__icon"><x-icon name="search" :size="16" /></span>
                <input type="search" class="search__input" placeholder="Search materials…"
                       aria-label="Search materials" data-table-search="#materialsTable">
                <button type="button" class="search__clear" aria-label="Clear search">
                    <x-icon name="x" :size="15" />
                </button>
            </div>

            <label class="sr-only" for="materialType">Filter by type</label>
            <select id="materialType" class="select" style="width:auto"
                    data-row-filter="#materialsTable" data-filter-key="type">
                <option value="">All types</option>
                <option value="PDF">PDF</option>
                <option value="Video">Video</option>
                <option value="Document">Document</option>
                <option value="Archive">Archive</option>
                <option value="Link">Link</option>
            </select>
        </div>

        <div class="table-wrap">
            <table class="table" id="materialsTable">
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
                                    <button type="button" class="btn-icon btn-icon--sm"
                                            data-modal-open="uploadModal" aria-label="Replace {{ $material->title }}">
                                        <x-icon name="edit" :size="15" />
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

{{-- ---------- UPLOAD MODAL ---------- --}}
<div class="modal" id="uploadModal" role="dialog" aria-modal="true"
     aria-labelledby="uploadTitle" aria-hidden="true">
    <div class="modal__dialog">
        <div class="modal__head">
            <div>
                <h3 id="uploadTitle">Upload material</h3>
                <p>Attach a resource to one of your lessons.</p>
            </div>
            <button type="button" class="btn-icon btn-icon--sm btn-icon--plain"
                    data-modal-close aria-label="Close dialog"><x-icon name="x" :size="18" /></button>
        </div>

        <form data-simulate-form="Material uploaded successfully ✓">
            <div class="modal__body">
                <div class="form">
                    <div class="field">
                        <label class="field__label" for="mTitle">Title <span class="req">*</span></label>
                        <input id="mTitle" name="title" type="text" class="input" required
                               placeholder="e.g. Flexbox Cheat Sheet">
                    </div>

                    <div class="form-grid">
                        <div class="field">
                            <label class="field__label" for="mType">Material type <span class="req">*</span></label>
                            <select id="mType" name="material_type" class="select" required>
                                <option value="">Choose a type…</option>
                                <option>PDF</option>
                                <option>Video</option>
                                <option>Document</option>
                                <option>Archive</option>
                                <option>Link</option>
                            </select>
                        </div>

                        <div class="field">
                            <label class="field__label" for="mLesson">Lesson <span class="req">*</span></label>
                            <select id="mLesson" class="select" required>
                                <option value="">Choose a lesson…</option>
                                @foreach ($lessons as $lesson)
                                    <option>{{ $lesson->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field__label" for="mDescription">Description</label>
                        <textarea id="mDescription" name="description" class="textarea" rows="3"
                                  placeholder="What is this file for?"></textarea>
                    </div>

                    <div class="field">
                        <label class="field__label">File</label>
                        <label class="dropzone" data-dropzone>
                            <span class="dropzone__icon" aria-hidden="true">📤</span>
                            <span class="dropzone__title">Drop your file here, or click to browse</span>
                            <span class="dropzone__hint">PDF, DOCX, ZIP, MP4 — up to 100 MB</span>
                            <input type="file">
                        </label>
                        <div data-file-list class="file-list"></div>
                    </div>
                </div>
            </div>

            <div class="modal__foot">
                <button type="button" class="btn btn--secondary" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn--primary">
                    <x-icon name="upload" :size="16" /> Upload
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
