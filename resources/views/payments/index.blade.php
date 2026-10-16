
@extends('layouts.app')

@section('title', 'Payments Management')
@section('page-title', 'Payments Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4 gap-3 flex-wrap">

    <div>
        <h5 class="mb-1">Payments List</h5>
        <p class="text-muted mb-0">
            Manage all project payments
        </p>
    </div>

    <a href="/payments/create" class="btn btn-primary">
        + Add Payment
    </a>

</div>

<!-- Total Received Card -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <h6 class="text-muted">Total Received</h6>

                <h3 class="text-success mb-0">
                    ${{ number_format($payments->sum('amount'), 2) }}
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover table-striped align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Project</th>
                        <th>Task</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>

                            <td>
                                {{ $payment->project?->client?->name ?? 'N/A' }}
                            </td>

                            <td class="fw-semibold">
                                {{ $payment->project?->title ?? 'N/A' }}
                            </td>

                            <td>{{ $payment->task }}</td>

                            <td class="text-success fw-semibold">
                                ${{ number_format($payment->amount, 2) }}
                            </td>

                            <td>{{ $payment->payment_date }}</td>

                            <td>{{ $payment->note }}</td>

                            <td>
                                <div class="d-flex gap-2">

                                    <a href="/payments/{{ $payment->id }}/edit"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form
                                        action="/payments/{{ $payment->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this payment?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="text-center text-muted py-4">
                                No payments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>
</div>

@endsection
