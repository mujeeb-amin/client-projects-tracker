
@extends('layouts.app')

@section('title', 'Projects Management')

@section('page-title', 'Projects Management')

@section('content')

@php
    $user = auth()->user();

    // Check Permissions
    $canCreate = $user->hasPermission('projects.create');
    $canEdit = $user->hasPermission('projects.edit');
    $canDelete = $user->hasPermission('projects.delete');

    // Show Actions column only when user can edit or delete
    $canManage = $canEdit || $canDelete;
@endphp


{{-- ================= PAGE HEADER ================= --}}

<div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">

    <div>
        <h5 class="mb-1">Projects List</h5>

        <p class="text-muted mb-0">
            Manage and track your projects
        </p>
    </div>

    {{-- Add Project Permission --}}
    @if($canCreate)

        <a href="{{ url('/projects/create') }}"
           class="btn btn-primary">
            + Add Project
        </a>

    @endif

</div>


{{-- ================= PROJECTS TABLE ================= --}}

<div class="card border-0 shadow-sm rounded-3">

    <div class="card-body">

        {{-- ================= PROJECTS LIVE SEARCH ================= --}}
        <div class="row g-2 align-items-center mb-3">
            <div class="col-12 col-md-6 col-lg-5">
                <label for="projectsSearch" class="form-label mb-1">
                    Search Projects
                </label>
                <input
                    type="search"
                    id="projectsSearch"
                    class="form-control"
                    placeholder="Search by client, project title, price or status..."
                    autocomplete="off">
            </div>
            <div class="col-12 col-md-auto">
                <small id="projectsSearchCount" class="text-muted" aria-live="polite"></small>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table table-hover table-striped align-middle" 	data-pagination="5" data-pagination-label="Projects">

                <thead class="table-dark">

                    <tr>
                        <th scope="col">No.</th>
                        <th>Client</th>
                        <th>Project Title</th>
                        <th>Agreed Price</th>
                        <th>Received</th>
                        <th>Remaining</th>
                        <th>Deadline</th>
                        <th>Status</th>

                        {{-- Actions Header --}}
                        @if($canManage)
                            <th>Actions</th>
                        @endif

                    </tr>

                </thead>


                <tbody>

                    @forelse($projects as $project)

                        <tr data-row-id="{{ $project->id }}">

                            {{-- ID --}}
                            <td data-row-number>{{ $loop->iteration }}</td>

                            {{-- Client --}}
                            <td>
                                {{ $project->client->name ?? 'N/A' }}
                            </td>

                            {{-- Project Title --}}
                            <td class="fw-semibold">
                                {{ $project->title }}
                            </td>

                            {{-- Agreed Price --}}
                            <td>
                                ${{ number_format($project->agreed_price, 2) }}
                            </td>

                            {{-- Received --}}
                            <td class="text-success fw-semibold">
                                ${{ number_format($project->payments_sum_amount ?? 0, 2) }}
                            </td>

                            {{-- Remaining --}}
                            <td class="text-danger fw-semibold">
                                ${{ number_format(
                                    $project->agreed_price - ($project->payments_sum_amount ?? 0),
                                    2
                                ) }}
                            </td>

                            {{-- Deadline --}}
                            <td>
                                {{ $project->deadline }}
                            </td>

                            {{-- Status --}}
                            <td>

                                @if($project->status == 'completed')

                                    <span class="badge bg-success">
                                        Completed
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        Ongoing
                                    </span>

                                @endif

                            </td>


                            {{-- ================= ACTIONS ================= --}}

                            @if($canManage)

                                <td>

                                    <div class="d-flex gap-2">

                                        {{-- Edit Project --}}
                                        @if($canEdit)

                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm js-edit"
                                                data-id="{{ $project->id }}">

                                                Edit

                                            </button>

                                        @endif


                                        {{-- Delete Project --}}
                                        @if($canDelete)

                                            <form
                                                action="{{ url('/projects/' . $project->id) }}"
                                                method="POST"
                                                class="js-ajax-delete">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm">

                                                    Delete

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            @endif

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="{{ $canManage ? 9 : 8 }}"
                                class="text-center text-muted py-4">

                                No projects found.

                            </td>

                        </tr>

                    @endforelse

                    <tr id="projectsSearchEmpty" hidden>
                        <td
                            colspan="{{ $canManage ? 9 : 8 }}"
                            class="text-center text-muted py-4">
                            No matching projects found.
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>



{{-- =====================================================
     AJAX EDIT PROJECT MODAL
     Only users with projects.edit permission
===================================================== --}}

@if($canEdit)

    <dialog
        id="ajaxEditDialog"
        class="ajax-edit-dialog"
        aria-labelledby="ajaxEditHeading">


        {{-- Modal Header --}}
        <div class="ajax-edit-head">

            <h5 class="mb-0" id="ajaxEditHeading">
                Edit Project
            </h5>

            <button
                type="button"
                class="btn-close"
                id="ajaxEditClose"
                aria-label="Close">
            </button>

        </div>


        {{-- Edit Form --}}
        <form id="ajaxEditForm">

            <div class="ajax-edit-body">

                {{-- Error Message --}}
                <div
                    id="ajaxEditErrors"
                    class="alert alert-danger d-none"
                    role="alert">
                </div>


                <div class="row g-3">

                    {{-- Client --}}
                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_client_id">

                            Client

                        </label>

                        <select
                            class="form-select"
                            id="edit_client_id"
                            name="client_id"
                            required>

                            @foreach($editClients as $option)

                                <option value="{{ $option->id }}">
                                    {{ $option->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Project Title --}}
                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_title">

                            Project Title

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_title"
                            name="title"
                            required>

                    </div>


                    {{-- Agreed Price --}}
                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_agreed_price">

                            Agreed Price

                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="edit_agreed_price"
                            name="agreed_price"
                            step="0.01"
                            min="0"
                            required>

                    </div>


                    {{-- Deadline --}}
                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_deadline">

                            Deadline

                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="edit_deadline"
                            name="deadline"
                            required>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_status">

                            Status

                        </label>

                        <select
                            class="form-select"
                            id="edit_status"
                            name="status"
                            required>

                            <option value="ongoing">
                                Ongoing
                            </option>

                            <option value="completed">
                                Completed
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            {{-- Modal Footer --}}
            <div class="ajax-edit-foot">

                <button
                    type="button"
                    class="btn btn-secondary"
                    id="ajaxEditCancel">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                    id="ajaxEditSave">

                    Save Changes

                </button>

            </div>

        </form>

    </dialog>


    {{-- Success Message --}}
    <div
        id="ajaxEditSuccess"
        class="alert alert-success mt-3 d-none"
        role="status">
    </div>


    {{-- ================= MODAL CSS ================= --}}

    @push('styles')

        <style>

            .ajax-edit-dialog {
                border: 0;
                border-radius: 14px;
                width: min(94vw, 680px);
                max-width: 680px;
                max-height: 90dvh;
                padding: 0;
                box-shadow: 0 24px 70px #0004;
            }

            .ajax-edit-dialog::backdrop {
                background: rgba(15, 23, 42, .58);
            }

            .ajax-edit-head,
            .ajax-edit-foot {
                padding: 16px 20px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
            }

            .ajax-edit-head {
                border-bottom: 1px solid #e5e7eb;
            }

            .ajax-edit-foot {
                border-top: 1px solid #e5e7eb;
                justify-content: flex-end;
            }

            .ajax-edit-body {
                padding: 20px;
                overflow: auto;
                max-height: calc(90dvh - 145px);
            }

            @media (max-width: 767.98px) {

                .ajax-edit-dialog {
                    width: 95vw;
                    max-height: 90dvh;
                }

                .ajax-edit-head,
                .ajax-edit-foot {
                    padding: 14px;
                }

                .ajax-edit-body {
                    padding: 14px;
                }

            }

        </style>

    @endpush



    {{-- ================= AJAX EDIT SCRIPT ================= --}}

    @push('scripts')

        <script>

        (() => {

            const dialog = document.getElementById('ajaxEditDialog');

            const form = document.getElementById('ajaxEditForm');

            const errors = document.getElementById('ajaxEditErrors');

            const save = document.getElementById('ajaxEditSave');

            const success = document.getElementById('ajaxEditSuccess');


            const editFields = [
                "client_id",
                "title",
                "agreed_price",
                "deadline",
                "status"
            ];


            let selectedId = null;

            let currentRow = null;


            const base = @json(url('/projects'));

            const csrf = document.querySelector(
                'meta[name="csrf-token"]'
            )?.content;


            if (!csrf) {
                console.warn('Missing csrf-token meta tag in app layout.');
            }


            // ================= ERROR FUNCTIONS =================

            const showError = message => {

                errors.textContent = message;

                errors.classList.remove('d-none');

            };


            const clearError = () => {

                errors.textContent = '';

                errors.classList.add('d-none');

            };


            // ================= AJAX REQUEST =================

            async function jsonRequest(url, options = {}) {

                const headers = {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(options.headers || {})
                };

                if (options.body) {

                    headers['Content-Type'] = 'application/json';

                }

                if (csrf) {

                    headers['X-CSRF-TOKEN'] = csrf;

                }

                const response = await fetch(url, {

                    ...options,

                    credentials: 'same-origin',

                    headers: headers

                });


                const data = await response.json().catch(() => ({}));


                if (!response.ok) {

                    const messages = data.errors
                        ? Object.values(data.errors).flat().join('\n')
                        : (
                            data.message ||
                            `Request failed (${response.status})`
                        );

                    throw new Error(messages);

                }

                return data;

            }


            // ================= EDIT PROJECT =================

            document.querySelectorAll('.js-edit').forEach(button => {

                button.addEventListener('click', async () => {

                    selectedId = button.dataset.id;

                    currentRow = button.closest('tr');

                    clearError();

                    success.classList.add('d-none');

                    form.reset();

                    dialog.showModal();

                    save.disabled = true;


                    try {

                        const record = await jsonRequest(
                            `${base}/${selectedId}/edit`
                        );

                editFields.forEach(key => {
                    const input = form.elements.namedItem(key);

                    if (input) {
                        input.value = record[key] == null
                            ? ''
                            : String(record[key]).slice(
                                0,
                                key === 'deadline' ? 10 : undefined
                            );

                        if (key === 'client_id') {
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                });


                    } catch (error) {

                        showError(error.message);

                    } finally {

                        save.disabled = false;

                    }

                });

            });


            // ================= CLOSE MODAL =================

            function closeDialog() {

                dialog.close();

            }


            document.getElementById('ajaxEditClose')
                .addEventListener('click', closeDialog);


            document.getElementById('ajaxEditCancel')
                .addEventListener('click', closeDialog);


            dialog.addEventListener('click', event => {

                if (event.target === dialog) {

                    closeDialog();

                }

            });


            // ================= SAVE CHANGES =================

            form.addEventListener('submit', async event => {

                event.preventDefault();

                clearError();

                if (!form.reportValidity()) {

                    return;

                }


                save.disabled = true;

                save.textContent = 'Saving...';


                try {

                    const payload = Object.fromEntries(
                        new FormData(form).entries()
                    );


                    const result = await jsonRequest(
                        `${base}/${selectedId}`,
                        {
                            method: 'PUT',
                            body: JSON.stringify(payload)
                        }
                    );


                    updateRow(result);

                    dialog.close();


                    success.textContent =
                        'Changes saved successfully.';

                    success.classList.remove('d-none');


                } catch (error) {

                    showError(error.message);

                } finally {

                    save.disabled = false;

                    save.textContent = 'Save Changes';

                }

            });


            // ================= UPDATE TABLE ROW =================

            function put(index, value) {

                const cell = currentRow?.cells[index];

                if (cell) {

                    cell.textContent = value ?? '';

                }

            }


            const cash = value => {

                return '$' + Number(value || 0).toLocaleString(
                    'en-US',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );

            };


            function updateRow(record) {

                put(1, record.client_name);

                put(2, record.title);

                put(3, cash(record.agreed_price));

                put(4, cash(record.received));

                put(5, cash(record.remaining));

                put(6, record.deadline);


                const badge = currentRow?.cells[7]?.querySelector('.badge');


                if (badge) {

                    badge.textContent = record.status === 'completed'
                        ? 'Completed'
                        : 'Ongoing';


                    badge.className = record.status === 'completed'
                        ? 'badge bg-success'
                        : 'badge bg-warning text-dark';

                }

            }

        })();

        </script>

    @endpush

@endif



{{-- =====================================================
     AJAX DELETE MODAL
     Only users with projects.delete permission
===================================================== --}}

@if($canDelete)

    @include('partials.ajax-delete-modal')

@endif


{{-- ================= PROJECTS LIVE SEARCH SCRIPT ================= --}}
@push('scripts')
    <script>
        (() => {
            const searchInput = document.getElementById('projectsSearch');
            const searchCount = document.getElementById('projectsSearchCount');
            const emptyRow = document.getElementById('projectsSearchEmpty');

            if (!searchInput) return;

            const projectRows = Array.from(
                document.querySelectorAll('tbody tr[data-row-id]')
            );

            function filterProjects() {
                const query = searchInput.value.trim().toLocaleLowerCase();
                let visibleCount = 0;

                projectRows.forEach(row => {
                    // Client, title, agreed/received/remaining amounts, deadline and status.
                    const searchableText = [
                        row.cells[1]?.textContent || '',
                        row.cells[2]?.textContent || '',
                        row.cells[3]?.textContent || '',
                        row.cells[4]?.textContent || '',
                        row.cells[5]?.textContent || '',
                        row.cells[6]?.textContent || '',
                        row.cells[7]?.textContent || ''
                    ].join(' ').toLocaleLowerCase();

                    const matches = searchableText.includes(query);
                    row.hidden = !matches;
                    if (matches) visibleCount++;
                });

                if (emptyRow) {
                    emptyRow.hidden = projectRows.length === 0 || visibleCount !== 0;
                }

                if (searchCount) {
                    searchCount.textContent = query
                        ? `${visibleCount} matching project(s)`
                        : `${projectRows.length} project(s)`;
                }
            }

            searchInput.addEventListener('input', filterProjects);
            filterProjects();
        })();
    </script>
@endpush

@endsection



