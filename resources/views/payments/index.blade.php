
@extends('layouts.app')

@section('title', 'Payments Management')
@section('page-title', 'Payments Management')

@section('content')

@php
    $user = auth()->user();

    $canCreate = $user && $user->hasPermission('payments.create');
    $canEdit = $user && $user->hasPermission('payments.edit');
    $canDelete = $user && $user->hasPermission('payments.delete');

    $canManage = $canEdit || $canDelete;
@endphp


<!-- ================= PAGE HEADER ================= -->

<div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">

    <div>
        <h5 class="mb-1">Payments List</h5>

        <p class="text-muted mb-0">
            Manage all project payments
        </p>
    </div>

    @if($canCreate)
        <a href="{{ url('/payments/create') }}" class="btn btn-primary">
            + Add Payment
        </a>
    @endif

</div>


<!-- ================= TOTAL RECEIVED CARD ================= -->

<div class="row mb-4">

    <div class="col-md-4">

        <div class="card border-0 shadow-sm rounded-3">

            <div class="card-body">

                <h6 class="text-muted">
                    Total Received
                </h6>

                <h3 id="paymentsTotalReceived" class="text-success mb-0">
                    ${{ number_format($payments->sum('amount'), 2) }}
                </h3>

            </div>

        </div>

    </div>

</div>


<!-- ================= PAYMENTS TABLE ================= -->

<div class="card border-0 shadow-sm rounded-3">

    <div class="card-body">


        {{-- ================= PAYMENTS LIVE SEARCH ================= --}}

        <div class="row g-2 align-items-center mb-3">

            <div class="col-12 col-md-6 col-lg-5">

                <label for="paymentsSearch" class="form-label mb-1">
                    Search Payments
                </label>

                <input
                    type="search"
                    id="paymentsSearch"
                    class="form-control"
                    placeholder="Search by client, project, task, amount, date or note..."
                    autocomplete="off">

            </div>

            <div class="col-12 col-md-auto">

                <small
                    id="paymentsSearchCount"
                    class="text-muted"
                    aria-live="polite">
                </small>

            </div>

        </div>


        <!-- ================= TABLE ================= -->

        <div class="table-responsive">

            <table class="table table-hover table-striped align-middle" 	data-pagination="10" data-pagination-label="Payments">

                <thead class="table-dark">

                    <tr>

                        <th scope="col">No.</th>
                        <th>Client</th>
                        <th>Project</th>
                        <th>Task</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Note</th>

                        @if($canManage)
                            <th>Actions</th>
                        @endif

                    </tr>

                </thead>


                <tbody id="paymentsTableBody">

                    @forelse($payments as $payment)

                        <tr data-row-id="{{ $payment->id }}">

                            <td data-row-number>{{ $loop->iteration }}</td>

                            <td>
                                {{ $payment->project?->client?->name ?? 'N/A' }}
                            </td>

                            <td class="fw-semibold">
                                {{ $payment->project?->title ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $payment->task }}
                            </td>

                            <td class="text-success fw-semibold">
                                ${{ number_format($payment->amount, 2) }}
                            </td>

                            <td>
                                {{ $payment->payment_date }}
                            </td>

                            <td>
                                {{ $payment->note }}
                            </td>


                            {{-- ================= ACTIONS ================= --}}

                            @if($canManage)

                                <td>

                                    <div class="d-flex gap-2">

                                        {{-- EDIT --}}

                                        @if($canEdit)

                                            <button
                                                type="button"
                                                class="btn btn-warning btn-sm js-edit"
                                                data-id="{{ $payment->id }}">

                                                Edit

                                            </button>

                                        @endif


                                        {{-- DELETE --}}

                                        @if($canDelete)

                                            <form
                                                action="{{ url('/payments/' . $payment->id) }}"
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
                                colspan="{{ $canManage ? 8 : 7 }}"
                                class="text-center text-muted py-4">

                                No payments found.

                            </td>

                        </tr>

                    @endforelse


                    {{-- NO SEARCH RESULTS --}}

                    <tr id="paymentsSearchEmpty" hidden>

                        <td
                            colspan="{{ $canManage ? 8 : 7 }}"
                            class="text-center text-muted py-4">

                            No matching payments found.

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>



{{-- =====================================================
    AJAX EDIT PAYMENT MODAL
    Only users with payments.edit permission
===================================================== --}}

@if($canEdit)

    <dialog
        id="ajaxEditDialog"
        class="ajax-edit-dialog"
        aria-labelledby="ajaxEditHeading">


        <!-- Modal Header -->

        <div class="ajax-edit-head">

            <h5 class="mb-0" id="ajaxEditHeading">
                Edit Payment
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


                    <!-- PROJECT -->

                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_project_id">

                            Project

                        </label>

                        <select
                            class="form-select"
                            id="edit_project_id"
                            name="project_id"
                            required>

                            @foreach($editProjects as $option)

                                <option value="{{ $option->id }}">

                                    {{ $option->title }} — {{ $option->client?->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- TASK -->

                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_task">

                            Task

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_task"
                            name="task">

                    </div>


                    <!-- AMOUNT -->

                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_amount">

                            Amount

                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="edit_amount"
                            name="amount"
                            step="0.01"
                            min="0.01"
                            required>

                    </div>


                    <!-- PAYMENT DATE -->

                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_payment_date">

                            Payment Date

                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="edit_payment_date"
                            name="payment_date"
                            required>

                    </div>


                    <!-- NOTE -->

                    <div class="col-md-6">

                        <label
                            class="form-label"
                            for="edit_note">

                            Note

                        </label>

                        <textarea
                            class="form-control"
                            id="edit_note"
                            name="note"
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
                    "project_id",
                    "task",
                    "amount",
                    "payment_date",
                    "note"
                ];


                let selectedId = null;

                let currentRow = null;


                const base = @json(url('/payments'));


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



                // ================= OPEN EDIT MODAL =================

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
                                            key.endsWith('_date') ||
                                            key === 'deadline'
                                                ? 10
                                                : undefined
                                        );

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


                const cash = value => '$' + Number(value || 0).toLocaleString(
                    'en-US',
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );


                function updateRow(record) {

                    put(1, record.client_name);

                    put(2, record.project_title);

                    put(3, record.task);

                    put(4, cash(record.amount));

                    put(5, record.payment_date);

                    put(6, record.note);


                    const total = document.getElementById(
                        'paymentsTotalReceived'
                    );


                    if (total && record.total_received != null) {

                        total.textContent = cash(record.total_received);

                    }


                    // Refresh search after AJAX edit

                    if (typeof window.filterPaymentsTable === 'function') {

                        window.filterPaymentsTable();

                    }

                }

            })();

        </script>

    @endpush

@endif



{{-- ================= AJAX DELETE MODAL ================= --}}

@if($canDelete)

    @include('partials.ajax-delete-modal')

@endif



{{-- ================= PAYMENTS LIVE SEARCH SCRIPT ================= --}}

@push('scripts')

    <script>

        (() => {

            const searchInput = document.getElementById('paymentsSearch');

            const searchCount = document.getElementById('paymentsSearchCount');

            const emptyRow = document.getElementById('paymentsSearchEmpty');


            if (!searchInput) {

                return;

            }


            const paymentRows = Array.from(

                document.querySelectorAll(
                    '#paymentsTableBody tr[data-row-id]'
                )

            );


            // ================= FILTER PAYMENTS =================

            function filterPayments() {

                const query = searchInput.value
                    .trim()
                    .toLocaleLowerCase();


                let visibleCount = 0;


                paymentRows.forEach(row => {

                    // Search Client, Project, Task, Amount, Date and Note

                    const searchableText = Array.from(row.cells)

                        .slice(1, 7)

                        .map(cell => cell.textContent || '')

                        .join(' ')

                        .toLocaleLowerCase();


                    const matches = searchableText.includes(query);


                    row.hidden = !matches;


                    if (matches) {

                        visibleCount++;

                    }

                });


                // No matching payments

                if (emptyRow) {

                    emptyRow.hidden =
                        paymentRows.length === 0 || visibleCount !== 0;

                }


                // Search result count

                if (searchCount) {

                    searchCount.textContent = query

                        ? `${visibleCount} matching payment(s)`

                        : `${paymentRows.length} payment(s)`;

                }

            }


            // Search while typing

            searchInput.addEventListener('input', filterPayments);


            // Initial search

            filterPayments();


            // Make search available after AJAX editing

            window.filterPaymentsTable = filterPayments;

        })();

    </script>

@endpush


@endsection
