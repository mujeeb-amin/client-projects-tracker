
@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@php
    $user = auth()->user();

    // Dashboard permissions
    $canViewClients = $user && $user->hasPermission('clients.view');
    $canViewProjects = $user && $user->hasPermission('projects.view');
    $canViewPayments = $user && $user->hasPermission('payments.view');
    $canViewReports = $user && $user->hasPermission('reports.view');

    // Check whether the user can see any dashboard section
    $hasDashboardContent = $canViewClients
        || $canViewProjects
        || $canViewPayments
        || $canViewReports;
@endphp

@push('styles')
<style>
    /* Dashboard cards act as accessible navigation links. */
    .dashboard-link-card {
        color: inherit;
        text-decoration: none;
        cursor: pointer;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .dashboard-link-card:hover {
        color: inherit;
        text-decoration: none;
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(15, 23, 42, .13) !important;
    }

    .dashboard-link-card:focus-visible {
        outline: 3px solid #2563eb;
        outline-offset: 3px;
    }

    @media (prefers-reduced-motion: reduce) {
        .dashboard-link-card {
            transition: none;
        }

        .dashboard-link-card:hover {
            transform: none;
        }
    }
</style>
@endpush

@section('content')

<!-- ===================================== -->
<!-- WELCOME BANNER -->
<!-- ===================================== -->

<div class="card border-0 shadow-sm mb-4"
     style="background: linear-gradient(135deg, #3044a5, #6775e8);">

    <div class="card-body p-4 p-lg-5">

        <div class="row align-items-center g-3">

            <div class="col-md-8">

                <p class="text-white fw-semibold mb-2">
                    CLIENT PROJECTS TRACKER
                </p>

                <h2 class="text-white fw-bold mb-3">
                    Welcome back, {{ auth()->user()?->name ?? 'User' }}!
                </h2>

                <p class="text-white mb-3">
                    Your clients, projects and payments —
                    all in one dashboard.
                </p>

                @if($canViewProjects)
                    <span class="badge bg-white text-dark p-2">
                        {{ $ongoingProjects }} Ongoing Projects
                    </span>
                @endif

            </div>

            <div class="col-md-4 text-center">

                <div style="font-size: 80px;">
                    📊
                </div>

            </div>

        </div>

    </div>

</div>


@if($hasDashboardContent)

    <!-- ===================================== -->
    <!-- STATISTICS -->
    <!-- ===================================== -->

    <div class="row g-4 mb-4">

        <!-- Total Clients -->
        @if($canViewClients)

            <div class="col-12 col-sm-6 col-xl-3">

                <a
                    href="{{ url('/clients') }}"
                    class="card border-0 shadow-sm h-100 dashboard-link-card"
                    aria-label="View Total Clients">

                    <div class="card-body">

                        <p class="text-muted mb-2">
                            Total Clients
                        </p>

                        <h2 class="fw-bold mb-0">
                            {{ $totalClients }}
                        </h2>

                        <div
                            class="bg-primary rounded mt-3"
                            style="height: 4px;">
                        </div>

                    </div>

                </a>

            </div>

        @endif


        <!-- Total Projects -->
        @if($canViewProjects)

            <div class="col-12 col-sm-6 col-xl-3">

                <a
                    href="{{ url('/projects') }}"
                    class="card border-0 shadow-sm h-100 dashboard-link-card"
                    aria-label="View Total Projects">

                    <div class="card-body">

                        <p class="text-muted mb-2">
                            Total Projects
                        </p>

                        <h2 class="fw-bold mb-0">
                            {{ $totalProjects }}
                        </h2>

                        <div
                            class="bg-info rounded mt-3"
                            style="height: 4px;">
                        </div>

                    </div>

                </a>

            </div>

        @endif


        <!-- Ongoing Projects -->
        @if($canViewProjects)

            <div class="col-12 col-sm-6 col-xl-3">

                <a
                    href="{{ url('/projects') }}"
                    class="card border-0 shadow-sm h-100 dashboard-link-card"
                    aria-label="View Ongoing Projects">

                    <div class="card-body">

                        <p class="text-muted mb-2">
                            Ongoing Projects
                        </p>

                        <h2 class="fw-bold mb-0">
                            {{ $ongoingProjects }}
                        </h2>

                        <div
                            class="bg-warning rounded mt-3"
                            style="height: 4px;">
                        </div>

                    </div>

                </a>

            </div>

        @endif


        <!-- Completed Projects -->
        @if($canViewProjects)

            <div class="col-12 col-sm-6 col-xl-3">

                <a
                    href="{{ url('/projects') }}"
                    class="card border-0 shadow-sm h-100 dashboard-link-card"
                    aria-label="View Completed Projects">

                    <div class="card-body">

                        <p class="text-muted mb-2">
                            Completed Projects
                        </p>

                        <h2 class="fw-bold mb-0">
                            {{ $completedProjects }}
                        </h2>

                        <div
                            class="bg-success rounded mt-3"
                            style="height: 4px;">
                        </div>

                    </div>

                </a>

            </div>

        @endif

    </div>


    <!-- ===================================== -->
    <!-- FINANCIAL OVERVIEW -->
    <!-- ===================================== -->

    @if($canViewReports)

        <div class="row g-4 mb-4">

            <!-- Total Agreed -->
            <div class="col-12 col-md-4">

                <a
                    href="{{ url('/projects') }}"
                    class="card border-0 shadow-sm h-100 dashboard-link-card"
                    aria-label="View Total Agreed">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Total Agreed
                        </h6>

                        <h3 class="fw-bold mb-0">
                            ${{ number_format($totalAgreed, 2) }}
                        </h3>

                    </div>

                </a>

            </div>


            <!-- Total Received -->
            <div class="col-12 col-md-4">

                <a
                    href="{{ url('/payments') }}"
                    class="card border-0 shadow-sm h-100 dashboard-link-card"
                    aria-label="View Total Received">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Total Received
                        </h6>

                        <h3 class="fw-bold mb-0 text-success">
                            ${{ number_format($totalReceived, 2) }}
                        </h3>

                    </div>

                </a>

            </div>


            <!-- Remaining Balance -->
            <div class="col-12 col-md-4">

                <a
                    href="{{ route('reports.index') }}"
                    class="card border-0 shadow-sm h-100 dashboard-link-card"
                    aria-label="View Remaining Balance">

                    <div class="card-body">

                        <h6 class="text-muted">
                            Remaining Balance
                        </h6>

                        <h3 class="fw-bold mb-0 text-danger">
                            ${{ number_format($remainingBalance, 2) }}
                        </h3>

                    </div>

                </a>

            </div>

        </div>

    @endif


    <!-- ===================================== -->
    <!-- RECENT PROJECTS -->
    <!-- ===================================== -->

    @if($canViewProjects)

        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">

                <h5 class="mb-0">
                    Recent Projects
                </h5>

                <a
                    href="{{ url('/projects') }}"
                    class="btn btn-outline-primary btn-sm">
                    View All
                </a>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover table-striped align-middle">

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

                                    <td>
                                        {{ $project->title }}
                                    </td>

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

                                    <td
                                        colspan="4"
                                        class="text-center text-muted">
                                        No projects found.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif


    <!-- ===================================== -->
    <!-- RECENT PAYMENTS -->
    <!-- ===================================== -->

    @if($canViewPayments)

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">

                <h5 class="mb-0">
                    Recent Payments
                </h5>

                <a
                    href="{{ url('/payments') }}"
                    class="btn btn-outline-primary btn-sm">
                    View All
                </a>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover table-striped align-middle">

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

                                    <td>
                                        {{ $payment->payment_date }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="4"
                                        class="text-center text-muted">
                                        No payments found.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif

@else

    <!-- ===================================== -->
    <!-- NO DASHBOARD CONTENT PERMISSION -->
    <!-- ===================================== -->

    <div class="card border-0 shadow-sm">

        <div class="card-body text-center py-5">

            <div class="mb-3" style="font-size: 45px;">
                🔒
            </div>

            <h4 class="mb-2">
                No Dashboard Data Available
            </h4>

            <p class="text-muted mb-0">
                You do not have permission to view dashboard statistics.
                Please contact your administrator.
            </p>

        </div>

    </div>

@endif

@endsection
