@extends('layouts.app')
@section('title', 'Payment Receipt')
@section('page-title', 'Payment Receipt')
@section('content')
<div class="row justify-content-center">
 <div class="col-lg-8">
  <div class="card border-0 shadow-sm rounded-3" id="paymentReceipt">
   <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Payment Receipt #{{ $payment->id }}</h5>
    <span class="badge bg-success">Saved</span>
   </div>
   <div class="card-body p-4">
    <h4 class="mb-1">CLIENT PROJECTS TRACKER</h4>
    <p class="text-muted mb-4">Payment confirmation</p>
    <div class="table-responsive">
     <table class="table table-bordered align-middle">
      <tbody>
       <tr><th style="width:40%">Client</th><td>{{ $project->client?->name ?? '—' }}</td></tr>
       <tr><th>Project</th><td>{{ $project->title }}</td></tr>
       <tr><th>Task</th><td>{{ $payment->task ?: '—' }}</td></tr>
       <tr><th>Payment Date</th><td>{{ $payment->payment_date }}</td></tr>
       <tr><th>Payment Amount</th><td class="fw-bold text-success">${{ number_format($payment->amount, 2) }}</td></tr>
       <tr><th>Project Price</th><td>${{ number_format($project->agreed_price, 2) }}</td></tr>
       <tr><th>Total Received</th><td>${{ number_format($totalReceived, 2) }}</td></tr>
       <tr><th>Remaining Balance</th><td>${{ number_format($balance, 2) }}</td></tr>
       <tr><th>Note</th><td style="white-space:pre-wrap">{{ $payment->note ?: '—' }}</td></tr>
      </tbody>
     </table>
    </div>
    <p class="small text-muted">Amounts are calculated from the current project payment records.</p>
   </div>
  </div>
  <div class="d-flex flex-wrap gap-2 justify-content-end mt-3">
   <a href="{{ url('/payments') }}" class="btn btn-secondary">Back to Payments</a>
   <button type="button" class="btn btn-outline-primary" onclick="window.print()">Print Report</button>
   <a href="{{ route('payments.receipt.pdf', $payment->id) }}" class="btn btn-primary">Download PDF</a>
  </div>
 </div>
</div>
@push('styles')
<style>
@media print {
 #header, #sidebar, #sidebarBackdrop, .sidebar-desktop-controls, .btn, .page-header { display:none!important; }
 #content.app-content { margin:0!important;padding:0!important;width:100%!important; }
 #paymentReceipt { box-shadow:none!important; }
 body { background:white!important; }
}
</style>
@endpush
@endsection
