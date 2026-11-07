<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('project.client')->get();

        $editProjects = Project::with('client')->get();

        return view('payments.index', compact('payments', 'editProjects'));
    }

public function create()
{
    $clients = Client::orderBy('name')
        ->get(['id', 'name']);

    $projects = Project::withSum('payments as total_paid', 'amount')
        ->orderBy('title')
        ->get([
            'id',
            'client_id',
            'title',
            'agreed_price'
        ]);

    return view('payments.create', compact('clients', 'projects'));
}

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'project_id' => [
                'required',
                Rule::exists('projects', 'id')
                    ->where('client_id', $request->input('client_id')),
            ],
            'task' => 'nullable|string|max:255',
            'amount' => 'required|numeric|gt:0',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $project = Project::findOrFail($request->project_id);

        $received = $project->payments()->sum('amount');

        $remaining = round(
            (float) $project->agreed_price - (float) $received,
            2
        );

        if (round((float) $request->amount, 2) > $remaining) {
            return back()
                ->withErrors([
                    'amount' => 'Payment exceeds remaining balance.',
                ])
                ->withInput();
        }

        $payment = new Payment();

        $payment->project_id = $request->project_id;
        $payment->task = $request->task;
        $payment->amount = $request->amount;
        $payment->payment_date = $request->payment_date;
        $payment->note = $request->note;

        $payment->save();

        return redirect()->route('payments.receipt', [
            'id' => $payment->id,
        ]);
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);

        $projects = Project::all();

        if (request()->expectsJson()) {
            return response()->json([
                'id' => $payment->id,
                'project_id' => $payment->project_id,
                'task' => $payment->task,
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date,
                'note' => $payment->note,
            ]);
        }

        return view('payments.edit', compact('payment', 'projects'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task' => 'nullable|string|max:255',
            'amount' => 'required|numeric|gt:0',
            'payment_date' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $payment = Payment::findOrFail($id);

        $project = Project::findOrFail($request->project_id);

        $received = $project->payments()
            ->where('id', '!=', $payment->id)
            ->sum('amount');

        $remaining = round(
            (float) $project->agreed_price - (float) $received,
            2
        );

        if (round((float) $request->amount, 2) > $remaining) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Payment exceeds remaining balance.',
                    'errors' => [
                        'amount' => [
                            'Payment exceeds remaining balance.',
                        ],
                    ],
                ], 422);
            }

            return back()
                ->withErrors([
                    'amount' => 'Payment exceeds remaining balance.',
                ])
                ->withInput();
        }

        $payment->project_id = $request->project_id;
        $payment->task = $request->task;
        $payment->amount = $request->amount;
        $payment->payment_date = $request->payment_date;
        $payment->note = $request->note;

        $payment->save();

        if ($request->expectsJson()) {
            $payment->load('project.client');

            return response()->json([
                'id' => $payment->id,
                'client_name' => $payment->project?->client?->name,
                'project_title' => $payment->project?->title,
                'task' => $payment->task,
                'amount' => $payment->amount,
                'payment_date' => $payment->payment_date,
                'note' => $payment->note,
                'total_received' => Payment::sum('amount'),
            ]);
        }

        return redirect('/payments')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Deleted successfully.',
                'id' => (int) $id,
            ]);
        }

        return redirect('/payments')
            ->with('success', 'Payment deleted successfully.');
    }

    public function receipt($id)
    {
        $payment = Payment::with('project.client')->findOrFail($id);

        $project = $payment->project;

        $totalReceived = (float) $project->payments()->sum('amount');

        $balance = round(
            (float) $project->agreed_price - $totalReceived,
            2
        );

        return view('payments.receipt', compact(
            'payment',
            'project',
            'totalReceived',
            'balance'
        ));
    }

public function downloadReceipt($id)
{
    $payment = Payment::with('project.client')->findOrFail($id);

    $project = $payment->project;

    $totalReceived = (float) $project->payments()->sum('amount');

    $balance = round(
        (float) $project->agreed_price - $totalReceived,
        2
    );

    $pdf = Pdf::loadView('payments.receipt-pdf', [
        'payment' => $payment,
        'project' => $project,
        'totalReceived' => $totalReceived,
        'balance' => $balance,
    ]);

    return $pdf->download('payment-receipt-' . $payment->id . '.pdf');
}
}