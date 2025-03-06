<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;

class HomeController extends Controller
{
    /**
     * Cria uma nova instância do controlador.
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
     * Mostra o painel da aplicação.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        //$this->authorize('is_admin');

        $clients = Clients::count();
        $users = User::count();
        $systems = Systems::count();
        //$files = Files::count();
        $files_arq = Files::where('type', 'NOT LIKE', '%POP%')
                          ->where('type', 'NOT LIKE', '%DADOS DE PRODUCAO%')
                          ->count();
        $files_proc = Files::where('type', 'LIKE', '%POP%')->count();
        $files_proddata = Files::where('type', 'LIKE', '%DADOS DE PRODUCAO%')->count();

        //dd($files_arq);
        
        return view('home.index', compact('clients', 'users', 'systems', 'files_arq', 'files_proc', 'files_proddata'));
    }
}