<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('project.client')->get();

        return view('payments.index', compact('payments'));
    }

    public function create(){
        $projects = Project::all();

        return view('payments.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
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
                    'amount' => 'Payment exceeds remaining balance.'
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

        return redirect('/payments')
            ->with('success', 'Payment added successfully.');
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);

        $projects = Project::all();

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
            return back()
                ->withErrors([
                    'amount' => 'Payment exceeds remaining balance.'
                ])
                ->withInput();
        }

        $payment->project_id = $request->project_id;
        $payment->task = $request->task;
        $payment->amount = $request->amount;
        $payment->payment_date = $request->payment_date;
        $payment->note = $request->note;

        $payment->save();

        return redirect('/payments')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->delete();

        return redirect('/payments')
            ->with('success', 'Payment deleted successfully.');
    }




}
