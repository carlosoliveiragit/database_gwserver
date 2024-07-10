<?php

namespace App\Http\Controllers;


use App\Models\User;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;

use Illuminate\Http\Request;

use stdClass;

class TelemetryController extends Controller
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
        $Clients = Clients::all();
        $Users = Users::all();
        $Systems = Systems::all();

        return view('uploads.telemetry.index', ['Clients' => $Clients], ['Systems' => $Systems], ['Users' => $Users]);
    }

    public function store(Request $request)
    {

        $file = new Files;

        $file->users_name = $request->users_name;
        $file->clients_client = $request->clients_client;
        $file->systems_system = $request->systems_system;
        $file->type = $request->type;
        $file->path = ('storage/received_file' . '/' . $request->clients_client . '/' . $request->systems_system . '/' . $request->type . '/');
          


        // upload Upload
        if($request->hasFile('upload') && $request->file('upload')->isValid()) {

        $requestUpload = $request->upload;

        $uploadName = strtolower($request->clients_client . '_' . $request->systems_system . '_' . $request->type . '_' . date("dmy_His") . ".json" );
        $find = " "; // espaço vazio
        $replace = "_"; // valor vazio
        $uploadName = strtolower(str_replace($find, $replace, $uploadName));

        $requestUpload->move(public_path($file->path), $uploadName);

        $file->file = $uploadName;

        }


        $file->save();

        return redirect('telemetry')->with('success', 'Upload Realizado com Sucesso');

    }

}