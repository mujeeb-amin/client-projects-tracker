<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ReportController extends Controller
{

public function index(\Illuminate\Http\Request $request)
{
    $request->validate([
        'start_date' => ['nullable', 'date'],
        'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
    ]);

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    $projects = \App\Models\Project::with('client')
        ->withSum('payments', 'amount')
        ->latest()
        ->get();

    $totalAgreed = $projects->sum('agreed_price');

    $totalReceived = $projects->sum(
        fn ($project) => $project->payments_sum_amount ?? 0
    );

    $remainingBalance = $totalAgreed - $totalReceived;

    $payments = \App\Models\Payment::with('project.client')
        ->when($startDate, function ($query) use ($startDate) {
            $query->whereDate('payment_date', '>=', $startDate);
        })
        ->when($endDate, function ($query) use ($endDate) {
            $query->whereDate('payment_date', '<=', $endDate);
        })
        ->orderByDesc('payment_date')
        ->get();

    $filteredReceived = $payments->sum('amount');

    return view('reports.index', compact(
        'projects',
        'totalAgreed',
        'totalReceived',
        'remainingBalance',
        'payments',
        'filteredReceived',
        'startDate',
        'endDate'
    ));
}

}