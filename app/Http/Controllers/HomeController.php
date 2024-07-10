<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use stdClass;

class HomeController extends Controller
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
        //$this->authorize('is_admin');

        $clients=   Clients::count();
        $users=     Users::count();
        $systems=   Systems::count();
        $files=     Files::count();

        //dd($files);
        
        return view('home.index',compact('clients','users','systems','files'));
    }

}
