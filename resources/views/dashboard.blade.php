
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')

<!-- Statistics -->
<div class="row g-4 mb-4">

    @foreach([
        ['Total Clients', $totalClients, 'primary'],
        ['Total Projects', $totalProjects, 'info'],
        ['Ongoing Projects', $ongoingProjects, 'warning'],
        ['Completed Projects', $completedProjects, 'success']
    ] as [$label, $value, $color])

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">{{ $label }}</p>
                    <h2 class="fw-bold mb-0">{{ $value }}</h2>
                    <div class="bg-{{ $color }} rounded mt-3"
                         style="height: 4px;"></div>
                </div>
            </div>
        </div>

    @endforeach

</div>

<!-- Financial Overview -->
<div class="row g-4 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Total Agreed</h6>
                <h3 class="fw-bold">
                    ${{ number_format($totalAgreed, 2) }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Total Received</h6>
                <h3 class="fw-bold text-success">
                    ${{ number_format($totalReceived, 2) }}
                </h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Remaining Balance</h6>
                <h3 class="fw-bold text-danger">
                    ${{ number_format($remainingBalance, 2) }}
                </h3>
            </div>
        </div>
    </div>

</div>

<!-- Recent Projects -->
<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Recent Projects</h5>
        <a href="/projects" class="btn btn-outline-primary btn-sm">
            View All
        </a>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Project</th>
                        <th>Client</th>
                        <th>Price</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentProjects as $project)
                        <tr>
                            <td>{{ $project->title }}</td>

                            <td>
                                {{ $project->client?->name ?? 'N/A' }}
                            </td>

                            <td>
                                ${{ number_format($project->agreed_price, 2) }}
                            </td>

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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                No projects found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

<!-- Recent Payments -->
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">Recent Payments</h5>
        <a href="/payments" class="btn btn-outline-primary btn-sm">
            View All
        </a>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Client</th>
                        <th>Project</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentPayments as $payment)
                        <tr>
                            <td>
                                {{ $payment->project?->client?->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $payment->project?->title ?? 'N/A' }}
                            </td>

                            <td class="text-success fw-semibold">
                                ${{ number_format($payment->amount, 2) }}
                            </td>

                            <td>{{ $payment->payment_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
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
