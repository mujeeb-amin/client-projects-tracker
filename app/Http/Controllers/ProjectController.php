<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function create(){
        $clients = Client::all();

        return view('projects.create', compact('clients'));
    }

    
    public function store(Request $request){

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|max:255',
            'agreed_price' => 'required|numeric|min:0',
            'deadline' => 'required|date',
            'status' => 'required|in:ongoing,completed',
        ]);

        $project = new Project();

        $project->client_id = $request->client_id;
        $project->title = $request->title;
        $project->agreed_price = $request->agreed_price;
        $project->deadline = $request->deadline;
        $project->status = $request->status;

        $project->save();

        return redirect('/projects')
            ->with('success', 'Project added successfully.');
    }

    public function index(){
        $projects = Project::with('client')->withSum('payments', 'amount')->get();

        return view('projects.index', compact('projects'));
    }

    public function edit($id){
        $project = Project::findOrFail($id);
        $clients = Client::all();

        return view('projects.edit', compact('project', 'clients'));
    }


    public function update(Request $request, $id){

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|max:255',
            'agreed_price' => 'required|numeric|min:0',
            'deadline' => 'required|date',
            'status' => 'required|in:ongoing,completed',
        ]);

        $project = Project::findOrFail($id);

        $project->client_id = $request->client_id;
        $project->title = $request->title;
        $project->agreed_price = $request->agreed_price;
        $project->deadline = $request->deadline;
        $project->status = $request->status;

        $project->save();

        return redirect('/projects')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy($id){
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect('/projects')
            ->with('success', 'Project deleted successfully.');
    }

    
}
