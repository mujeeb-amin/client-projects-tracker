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

        return redirect('/clients')
            ->with('success', 'Client updated successfully.');

    }

    public function destroy($id){
        
        $client = Client::findOrFail($id);
        $client->delete();
        return redirect('/clients')->with('success', 'Client deleted successfully');

    }


}
