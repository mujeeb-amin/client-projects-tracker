
@extends('layouts.app')

@section('title', 'Payment Receipt')
@section('page-title', 'Payment Receipt')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- RECEIPT --}}
        <div class="card border-0 shadow-sm rounded-3" id="paymentReceipt">

            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    Payment Receipt #{{ $payment->id }}
                </h5>

                <span class="badge bg-success">
                    Saved
                </span>
            </div>

            <div class="card-body p-4">

                <h4 class="mb-1">
                    CLIENT PROJECTS TRACKER
                </h4>

                <p class="text-muted mb-4">
                    Payment confirmation
                </p>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <tbody>
                            <tr>
                                <th style="width:40%">Client</th>
                                <td>{{ $project->client?->name ?? '—' }}</td>
                            </tr>

                            <tr>
                                <th>Project</th>
                                <td>{{ $project->title }}</td>
                            </tr>

                            <tr>
                                <th>Task</th>
                                <td>{{ $payment->task ?: '—' }}</td>
                            </tr>

                            <tr>
                                <th>Payment Date</th>
                                <td>{{ $payment->payment_date }}</td>
                            </tr>

                            <tr>
                                <th>Payment Amount</th>
                                <td class="fw-bold text-success">
                                    ${{ number_format($payment->amount, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Project Price</th>
                                <td>
                                    ${{ number_format($project->agreed_price, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Total Received</th>
                                <td>
                                    ${{ number_format($totalReceived, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Remaining Balance</th>
                                <td>
                                    ${{ number_format($balance, 2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Note</th>
                                <td style="white-space:pre-wrap">
                                    {{ $payment->note ?: '—' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="small text-muted mb-0">
                    Amounts are calculated from the current project payment records.
                </p>

            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="d-flex flex-wrap gap-2 justify-content-end mt-3 receipt-actions">

            <a href="{{ url('/payments') }}" class="btn btn-secondary">
                Back to Payments
            </a>

            <button
                type="button"
                class="btn btn-outline-primary"
                onclick="window.print()">
                Print Report
            </button>

        </div>

    </div>
</div>

{{-- PRINT SETTINGS --}}
@push('styles')
<style>
    #paymentReceipt {
        background: #fff;
    }

    @media print {

        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        html,
        body {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        body * {
            visibility: hidden !important;
        }

        #paymentReceipt,
        #paymentReceipt * {
            visibility: visible !important;
        }

        #paymentReceipt {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            border-radius: 0 !important;
            background: #fff !important;
        }

        #paymentReceipt .card-header {
            background: #fff !important;
        }

        #paymentReceipt .card-body {
            padding: 20px !important;
        }

        #paymentReceipt .table-responsive {
            overflow: visible !important;
        }

        #paymentReceipt table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        #paymentReceipt tr {
            page-break-inside: avoid !important;
        }

        #paymentReceipt th,
        #paymentReceipt td {
            border: 1px solid #ccc !important;
            padding: 10px !important;
        }

        #paymentReceipt .text-success {
            color: #15803d !important;
        }

        .receipt-actions,
        .btn,
        #header,
        #sidebar,
        #sidebarBackdrop,
        .sidebar-desktop-controls,
        .page-header {
            display: none !important;
        }
    }
</style>
@endpush

@endsection
