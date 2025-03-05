<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClpAbbController extends Controller
{
    protected $user;

    public function __construct(Users $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function index()
    {
        $Clients = Clients::all();
        $Users = Users::all();
        $Systems = Systems::all();

        return view('uploads.clp_abb.index', compact('Clients', 'Users', 'Systems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'upload' => 'required|file|max:100000', // Máximo 100MB, validamos a extensão depois
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'type' => 'required|string',
            'model' => 'required|string'
        ]);

        if ($request->hasFile('upload') && $request->file('upload')->isValid()) {
            $requestUpload = $request->file('upload');
            $extension = strtolower($requestUpload->getClientOriginalExtension());

            // Validação manual da extensão
            if ($extension !== 'projectarchive') {
                return redirect()->back()->withInput()->with('error', 'O arquivo deve ter a extensão .projectarchive.');
            }

            // Criando o caminho seguro para armazenamento
            $directoryPath = 'private/received_file/' . $request->clients_client . '/' . $request->systems_system . '/' . "CLP". '/' . $request->model . '/';
            Storage::makeDirectory($directoryPath);

            // Criando nome seguro para o arquivo
            $uploadName = strtoupper(str_replace(
                [" - ", "-", " "],
                "_",
                $request->clients_client . '_' . $request->systems_system . '_' . $request->type_Ident . '_' . $request->model . '_' . date("dmy_His")
            ));

            // Adiciona a extensão em minúsculas
            $uploadName .= '.' . $extension;

            // Salvando o arquivo no storage
            $requestUpload->storeAs($directoryPath, $uploadName, 'local');

            // Salvando as informações no banco
            $file = new Files;
            $file->users_name = $request->users_name;
            $file->clients_client = $request->clients_client;
            $file->systems_system = $request->systems_system;
            $file->type = "CLP";
            $file->sector = "MANUTENCAO";
            $file->path = $directoryPath;
            $file->file = $uploadName;
            $file->save();
        }

        return redirect('clp_abb')->with('success', 'Upload realizado com sucesso!');
    }
}
