<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // نمایش فرم افزودن پروژه
    public function create()
    {
        $clients = Client::orderBy('name')->get();

        return view('projects.create', compact('clients'));
    }

    // ذخیره پروژه جدید
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'agreed_price' => 'required|numeric|min:0',
            'deadline' => 'required|date',
            'status' => 'required|in:ongoing,completed',
        ]);

        Project::create($validated);

        return redirect('/projects')
            ->with('success', 'Project added successfully.');
    }

    // نمایش لیست پروژه‌ها
    public function index()
    {
        $projects = Project::with('client')
            ->withSum('payments', 'amount')
            ->latest()
            ->get();

        $editClients = Client::orderBy('name')
            ->get(['id', 'name']);

        return view('projects.index', compact(
            'projects',
            'editClients'
        ));
    }

    // نمایش اطلاعات یک پروژه برای ویرایش
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json([
                'id' => $project->id,
                'client_id' => $project->client_id,
                'title' => $project->title,
                'agreed_price' => $project->agreed_price,
                'deadline' => $project->deadline,
                'status' => $project->status,
            ]);
        }

        $clients = Client::orderBy('name')->get();

        return view('projects.edit', compact(
            'project',
            'clients'
        ));
    }

    // به‌روزرسانی پروژه
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'agreed_price' => 'required|numeric|min:0',
            'deadline' => 'required|date',
            'status' => 'required|in:ongoing,completed',
        ]);

        $project = Project::findOrFail($id);

        $project->update($validated);

        if ($request->expectsJson()) {

            $project->load('client');

            $received = (float) $project->payments()
                ->sum('amount');

            $remaining = (float) $project->agreed_price
                - $received;

            return response()->json([
                'id' => $project->id,
                'client_name' => $project->client?->name,
                'title' => $project->title,
                'agreed_price' => (float) $project->agreed_price,
                'received' => $received,
                'remaining' => $remaining,
                'deadline' => $project->deadline,
                'status' => $project->status,
            ]);
        }

        return redirect('/projects')
            ->with('success', 'Project updated successfully.');
    }

    // حذف پروژه
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        $project->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Deleted successfully.',
                'id' => (int) $id,
            ]);
        }

        return redirect('/projects')
            ->with('success', 'Project deleted successfully.');
    }
}