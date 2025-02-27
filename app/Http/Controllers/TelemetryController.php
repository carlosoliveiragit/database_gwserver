<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TelemetryController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function index()
    {
        $Clients = Clients::all();
        $Users = Users::all();
        $Systems = Systems::all();

        return view('uploads.telemetry.index', compact('Clients', 'Users', 'Systems'));
    }


    public function store(Request $request){


        $request->validate([
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'type' => 'required|string',
            'upload' => 'nullable|file|mimetypes:application/json,text/plain|max:2048',
            'json_text' => 'nullable|string'
        ]);
        

        $file = new Files;
        $file->users_name = $request->users_name;
        $file->clients_client = $request->clients_client;
        $file->systems_system = $request->systems_system;
        $file->type = $request->type;
        $file->sector = "CCO";

        $directoryPath = 'storage/received_file/' . $request->clients_client . '/' . $request->systems_system . '/' . $request->type . '/';
        
        if (!file_exists(public_path($directoryPath))) {
            mkdir(public_path($directoryPath), 0777, true);
        }

        // Se o usuário enviou um arquivo JSON
        if ($request->hasFile('upload') && $request->file('upload')->isValid()) {
            $uploadName = strtolower(str_replace(" ", "_", 
                $request->clients_client . '_' . $request->systems_system . '_' . $request->type . '_' . date("dmy_His") . ".json"));

            $request->file('upload')->move(public_path($directoryPath), $uploadName);
            $file->file = $uploadName;

        // Se o usuário colou o JSON manualmente
        } elseif ($request->json_text) {
            $uploadName = strtolower(str_replace(" ", "_", 
                $request->clients_client . '_' . $request->systems_system . '_' . $request->type . '_' . date("dmy_His") . ".json"));

            file_put_contents(public_path($directoryPath . $uploadName), $request->json_text);
            $file->file = $uploadName;
        } else {
            return redirect()->back()->with('error', 'Nenhum JSON enviado.');
        }
        //dd($request->file('upload')->getMimeType());

        $file->path = $directoryPath;
        $file->save();


        return redirect('telemetry')->with('success', 'Upload Realizado com Sucesso!');
    }
}