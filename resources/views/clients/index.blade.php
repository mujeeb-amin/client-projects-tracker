@extends('layouts.app')

@section('title', 'Clients Management')

@section('page-title', 'Clients Management')

@section('content')

@php
    $user = auth()->user();

    $canCreate = $user->hasPermission('clients.create');
    $canEdit   = $user->hasPermission('clients.edit');
    $canDelete = $user->hasPermission('clients.delete');

    $canManage = $canEdit || $canDelete;
@endphp


<!-- ================= PAGE HEADER ================= -->
<div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">

    <div>
        <h5 class="mb-1">Clients List</h5>

        <p class="text-muted mb-0">
            Manage all your clients
        </p>
    </div>

    {{-- Add Client --}}
    @if($canCreate)
        <a href="{{ url('/clients/create') }}"
           class="btn btn-primary">

            + Add Client

        </a>
    @endif

</div>


<!-- ================= CLIENTS TABLE ================= -->
<div class="card border-0 shadow-sm rounded-3">

    <div class="card-body">

        {{-- ================= CLIENTS LIVE SEARCH ================= --}}
        <div class="row g-2 align-items-center mb-3">
            <div class="col-12 col-md-6 col-lg-5">
                <label for="clientsSearch" class="form-label mb-1">
                    Search Clients
                </label>
                <input
                    type="search"
                    id="clientsSearch"
                    class="form-control"
                    placeholder="Search by name, phone, email, company or address..."
                    autocomplete="off">
            </div>
            <div class="col-12 col-md-auto">
                <small id="clientsSearchCount" class="text-muted" aria-live="polite"></small>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table table-hover table-striped align-middle" data-pagination="4" data-pagination-label="Clients">

                <thead class="table-dark">

                    <tr>
                        <th scope="col">No.</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Address</th>

                        @if($canManage)
                            <th>Actions</th>
                        @endif
                    </tr>

                </thead>

                <tbody>

                    @forelse($clients as $client)

                        <tr data-row-id="{{ $client->id }}">

                            <td data-row-number>{{ $loop->iteration }}</td>

                            <td class="fw-semibold">
                                {{ $client->name }}
                            </td>

                            <td>
                                {{ $client->phone }}
                            </td>

                            <td>
                                {{ $client->email }}
                            </td>

                            <td>
                                {{ $client->company_name }}
                            </td>

                            <td>
                                {{ $client->address }}
                            </td>


                            {{-- Actions --}}
                            @if($canManage)

                                <td>

                                    <div class="d-flex gap-2">

                                        {{-- Edit Client --}}
                                        @if($canEdit)

                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm js-edit"
                                                data-id="{{ $client->id }}">

                                                Edit

                                            </button>

                                        @endif


                                        {{-- Delete Client --}}
                                        @if($canDelete)

                                            <form
                                                action="{{ url('/clients/' . $client->id) }}"
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
                                colspan="{{ $canManage ? 7 : 6 }}"
                                class="text-center text-muted py-4">

                                No clients found.

                            </td>

                        </tr>

                    @endforelse

                    {{-- Shown only when a search has no matching clients --}}
                    <tr id="clientsSearchEmpty" hidden>
                        <td
                            colspan="{{ $canManage ? 7 : 6 }}"
                            class="text-center text-muted py-4">
                            No matching clients found.
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>



{{-- =====================================================
    AJAX EDIT CLIENT MODAL
    Only users with clients.edit permission
===================================================== --}}

@if($canEdit)

    <dialog
        id="ajaxEditDialog"
        class="ajax-edit-dialog"
        aria-labelledby="ajaxEditHeading">

        <!-- Modal Header -->
        <div class="ajax-edit-head">

            <h5 class="mb-0" id="ajaxEditHeading">
                Edit Client
            </h5>

            <button
                type="button"
                class="btn-close"
                id="ajaxEditClose"
                aria-label="Close">
            </button>

        </div>


        <!-- Edit Form -->
        <form id="ajaxEditForm">

            <div class="ajax-edit-body">

                <!-- Errors -->
                <div
                    id="ajaxEditErrors"
                    class="alert alert-danger d-none"
                    role="alert">
                </div>


                <div class="row g-3">

                    <!-- Name -->
                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_name">

                            Name

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_name"
                            name="name"
                            required>

                    </div>


                    <!-- Phone -->
                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_phone">

                            Phone

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_phone"
                            name="phone"
                            required>

                    </div>


                    <!-- Email -->
                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_email">

                            Email

                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="edit_email"
                            name="email">

                    </div>


                    <!-- Company -->
                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_company_name">

                            Company

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_company_name"
                            name="company_name">

                    </div>


                    <!-- Address -->
                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_address">

                            Address

                        </label>

                        <textarea
                            class="form-control"
                            id="edit_address"
                            name="address"
                            rows="2"></textarea>

                    </div>

                </div>

            </div>


            <!-- Modal Footer -->
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


    <!-- Success Message -->
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
                "name",
                "phone",
                "email",
                "company_name",
                "address"
            ];

            let selectedId = null;

            let currentRow = null;

            const base = @json(url('/clients'));

            const csrf = document.querySelector(
                'meta[name="csrf-token"]'
            )?.content;

            if (!csrf) {
                console.warn(
                    'Missing csrf-token meta tag in app layout.'
                );
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


            // ================= EDIT CLIENT =================

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
                                    : String(record[key]);

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


            function updateRow(record) {

                put(1, record.name);

                put(2, record.phone);

                put(3, record.email);

                put(4, record.company_name);

                put(5, record.address);

            }

        })();
        </script>

    @endpush

@endif



{{-- =====================================================
    AJAX DELETE MODAL
    Only users with clients.delete permission
===================================================== --}}

@if($canDelete)

    @include('partials.ajax-delete-modal')

@endif


{{-- ================= CLIENTS LIVE SEARCH SCRIPT ================= --}}
@push('scripts')
    <script>
        (() => {
            const searchInput = document.getElementById('clientsSearch');
            const searchCount = document.getElementById('clientsSearchCount');
            const emptyRow = document.getElementById('clientsSearchEmpty');

            if (!searchInput) {
                return;
            }

            const clientRows = Array.from(
                document.querySelectorAll('tbody tr[data-row-id]')
            );

            function filterClients() {
                const query = searchInput.value.trim().toLocaleLowerCase();
                let visibleCount = 0;

                clientRows.forEach(row => {
                    // Search across Name, Phone, Email, Company and Address.
                    const searchableText = Array.from(row.cells)
                        .slice(1, 6)
                        .map(cell => cell.textContent || '')
                        .join(' ')
                        .toLocaleLowerCase();

                    const matches = searchableText.includes(query);

                    row.hidden = !matches;

                    if (matches) {
                        visibleCount++;
                    }
                });

                if (emptyRow) {
                    emptyRow.hidden = clientRows.length === 0 || visibleCount !== 0;
                }

                if (searchCount) {
                    searchCount.textContent = query
                        ? `${visibleCount} matching client(s)`
                        : `${clientRows.length} client(s)`;
                }
            }

            searchInput.addEventListener('input', filterClients);
            filterClients();
        })();
    </script>
@endpush

@endsection