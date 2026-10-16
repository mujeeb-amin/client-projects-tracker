
@extends('layouts.app')

@section('title', 'Financial Reports')
@section('page-title', 'Financial Reports')

@section('content')

<!-- ===================================== -->
<!-- DATE FILTER -->
<!-- ===================================== -->

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Filter Payments by Date</h5>
    </div>

    <div class="card-body">

        <form action="{{ route('reports.index') }}" method="GET">

            <div class="row g-3 align-items-end">

                <!-- Start Date -->
                <div class="col-md-4">

                    <label for="start_date" class="form-label">
                        Start Date
                    </label>

                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ old('start_date', $startDate) }}"
                        class="form-control @error('start_date') is-invalid @enderror"
                    >

                    @error('start_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- End Date -->
                <div class="col-md-4">

                    <label for="end_date" class="form-label">
                        End Date
                    </label>

                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ old('end_date', $endDate) }}"
                        class="form-control @error('end_date') is-invalid @enderror"
                    >

                    @error('end_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- Buttons -->
                <div class="col-md-4">

                    <div class="d-flex gap-2">

                        <button type="submit"
                                class="btn btn-primary flex-fill">
                            Filter
                        </button>

                        <a href="{{ route('reports.index') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


<!-- ===================================== -->
<!-- SUMMARY CARDS -->
<!-- ===================================== -->

<div class="row g-4 mb-4">

    <!-- Total Agreed -->
    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    Total Agreed
                </h6>

                <h3 class="fw-bold mb-0">
                    ${{ number_format($totalAgreed, 2) }}
                </h3>

            </div>

        </div>

    </div>

    <!-- Total Received -->
    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    Total Received
                </h6>

                <h3 class="fw-bold text-success mb-0">
                    ${{ number_format($totalReceived, 2) }}
                </h3>

            </div>

        </div>

    </div>

    <!-- Remaining Balance -->
    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <h6 class="text-muted">
                    Remaining Balance
                </h6>

                <h3 class="fw-bold text-danger mb-0">
                    ${{ number_format($remainingBalance, 2) }}
                </h3>

            </div>

        </div>

    </div>

</div>


<!-- ===================================== -->
<!-- OVERALL PAYMENT PROGRESS -->
<!-- ===================================== -->

@php
    $overallProgress = $totalAgreed > 0
        ? min(100, max(0, ($totalReceived / $totalAgreed) * 100))
        : 0;
@endphp

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <div class="d-flex justify-content-between mb-2">

            <h6 class="mb-0">
                Overall Payment Progress
            </h6>

            <span class="fw-semibold">
                {{ number_format($overallProgress, 1) }}%
            </span>

        </div>

        <div class="progress" style="height: 15px;">

            <div
                class="progress-bar bg-success"
                role="progressbar"
                style="width: {{ $overallProgress }}%;"
                aria-valuenow="{{ $overallProgress }}"
                aria-valuemin="0"
                aria-valuemax="100">
            </div>

        </div>

    </div>

</div>


<!-- ===================================== -->
<!-- FILTERED RECEIVED -->
<!-- ===================================== -->

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <div>

                <h6 class="text-muted mb-1">
                    Received in Selected Period
                </h6>

                <h3 class="fw-bold text-success mb-0">
                    ${{ number_format($filteredReceived, 2) }}
                </h3>

            </div>

            <span class="badge bg-primary fs-6">
                {{ $payments->count() }} Payments
            </span>

        </div>

        <small class="text-muted d-block mt-2">
            @if($startDate || $endDate)
                Period:
                {{ $startDate ?: 'Beginning' }}
                to
                {{ $endDate ?: 'Today / Latest' }}
            @else
                Showing all payment dates
            @endif
        </small>

    </div>

</div>


<!-- ===================================== -->
<!-- PAYMENTS IN SELECTED PERIOD -->
<!-- ===================================== -->

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">

        <h5 class="mb-0">
            Payments in Selected Period
        </h5>

        <span class="badge bg-secondary">
            {{ $payments->count() }} Records
        </span>

    </div>

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
                    </tr>

                </thead>

                <tbody>

                    @forelse($payments as $payment)

                        <tr>

                            <td>
                                {{ $payment->id }}
                            </td>

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
                                {{ $payment->note ?? '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center text-muted py-4">

                                No payments found for this period.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- ===================================== -->
<!-- PROJECTS FINANCIAL DETAILS -->
<!-- ===================================== -->

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0">
            Projects Financial Details
        </h5>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover table-striped align-middle">

                <thead class="table-dark">

                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Project</th>
                        <th>Agreed</th>
                        <th>Received</th>
                        <th>Remaining</th>
                        <th>Progress</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($projects as $project)

                        @php
                            $received = $project->payments_sum_amount ?? 0;

                            $remaining = $project->agreed_price - $received;

                            $progress = $project->agreed_price > 0
                                ? min(100, max(0, ($received / $project->agreed_price) * 100))
                                : 0;
                        @endphp

                        <tr>

                            <td>
                                {{ $project->id }}
                            </td>

                            <td>
                                {{ $project->client?->name ?? 'N/A' }}
                            </td>

                            <td class="fw-semibold">
                                {{ $project->title }}
                            </td>

                            <td>
                                ${{ number_format($project->agreed_price, 2) }}
                            </td>

                            <td class="text-success fw-semibold">
                                ${{ number_format($received, 2) }}
                            </td>

                            <td class="text-danger fw-semibold">
                                ${{ number_format($remaining, 2) }}
                            </td>

                            <td style="min-width: 150px;">

                                <div class="progress mb-1"
                                     style="height: 10px;">

                                    <div
                                        class="progress-bar bg-success"
                                        role="progressbar"
                                        style="width: {{ $progress }}%;"
                                        aria-valuenow="{{ $progress }}"
                                        aria-valuemin="0"
                                        aria-valuemax="100">
                                    </div>

                                </div>

                                <small class="text-muted">
                                    {{ number_format($progress, 1) }}%
                                </small>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center text-muted py-4">

                                No financial records found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
