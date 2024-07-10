<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Storage;

//use App\Models\Users;
//use App\Models\Clients;
//use App\Models\Systems;
use App\Models\Files;

use stdClass;

class FilesController extends Controller
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

        //$Clients=   Clients::all();
        //$Users=     Users::all();
        //$Systems=   Systems::all();
        $Files=     Files::all();

        return view('files.index',['Files'=> $Files]);
    }

    public function destroy($id, Request $request) {

         $Files = new Files;
         $Files -> path = $request -> path;
         $Files -> file = $request -> file;


        Files::findOrFail($id)->delete();
        unlink($request -> path . $request -> file);
        
        return redirect('files')->with('success', 'Arquivo Deletado com Sucesso');
        

    }

}
