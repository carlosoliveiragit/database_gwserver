<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Clients;

class ClientsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $this->authorize('is_admin');
        $Clients= Clients::all();
        return view('clients.index',compact('Clients'));
    }

    public function store(Request $request){

        $clients = new Clients;

        $clients -> name = $request -> client;
        
        $clients -> save();

        return redirect('clients')->with('success', 'Cliente Cadastrado');
    }
    public function edit($id) {

        $Clients = Clients::findOrFail($id);

        return view('update.edit_client.index', ['Clients' => $Clients]);

    }
    
    public function update(Request $request) {

        $data = $request->all();

        Clients::findOrFail($request->id)->update($data);

        return redirect('clients')->with('success', 'Cliente Atualizado com Sucesso');

    }

    public function destroy($id) {

        Clients::findOrFail($id)->delete();

        return redirect('clients')->with('success', 'Cliente Deletado com Sucesso');

    }

}
