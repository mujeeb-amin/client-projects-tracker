<?php

namespace App\Http\Controllers;

use App\Models\Client;

use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index(){

        $clients = Client::all();

        return view('clients.index', compact('clients'));
    }

    public function create(){
        return view('clients.create');
    }


    public function store(Request $request){
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:10',
            'email' => 'required|email',
            'company_name' => 'nullable|max:255',
            'address' => 'nullable',
        ]);

        $client = new Client();

        $client->name = $request->name;
        $client->phone = $request->phone;
        $client->email = $request->email;
        $client->company_name = $request->company_name;
        $client->address = $request->address;

        $client->save();

        return redirect('/clients')->with('success', 'Client added successfully');
    }

    public function edit($id){
        $client = Client::findOrFail($id);
        
        if (request()->expectsJson()) return response()->json($client->only(['id','name','phone','email','company_name','address']));
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, $id){

        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:50',
            'email' => 'nullable|email',
            'company_name' => 'nullable|max:255',
            'address' => 'nullable',
    ]);

        $client = Client::findOrFail($id);

        $client->name = $request->name;
        $client->phone = $request->phone;
        $client->email = $request->email;
        $client->company_name = $request->company_name;
        $client->address = $request->address;

        $client->save();

        if ($request->expectsJson()) return response()->json($client->only(['id','name','phone','email','company_name','address']));
        return redirect('/clients')->with('success', 'Client updated successfully.');

    }
public function destroy($id)
{
    $client = Client::findOrFail($id);

    // Check related projects
    $hasProjects = \App\Models\Project::where(
        'client_id',
        $client->id
    )->exists();

    if ($hasProjects) {

        $message = 'This client has existing projects and cannot be deleted. Please delete or reassign the related projects first.';

        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message
            ], 409);
        }

        return redirect('/clients')
            ->withErrors(['delete' => $message]);
    }

    $client->delete();

    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Client deleted successfully.'
        ]);
    }

    return redirect('/clients')
        ->with('success', 'Client deleted successfully.');
}


}
