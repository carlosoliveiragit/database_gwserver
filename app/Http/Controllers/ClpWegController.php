<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClpWegController extends Controller
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

        return view('uploads.clp_weg.index', compact('Clients', 'Users', 'Systems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'upload' => 'required|file|max:100000', // Máximo 100MB, validamos a extensão depois
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'type_Ident' => 'required|string',
            'model' => 'required|string'
        ]);

        if ($request->hasFile('upload') && $request->file('upload')->isValid()) {
            $requestUpload = $request->file('upload');
            $extension = strtolower($requestUpload->getClientOriginalExtension());

            // Mapeamento de extensões válidas por modelo
            $validExtensions = [
                'PLC300' => 'bkp',
                'CLIC02' => 'cli',
                'PLC500' => 'projectarchive',
            ];

            // Validação da extensão para o modelo selecionado
            if (!isset($validExtensions[$request->model]) || $validExtensions[$request->model] !== $extension) {
                return redirect()->back()->withInput()->with('error', 'Extensão: ' . $extension . ' não é compatível com o modelo ' . $request->model);
            }

            // Criando o caminho seguro para armazenamento
            $directoryPath = 'private/received_file/' . $request->clients_client . '/' . $request->systems_system . '/' . $request->type . '_' . $request->model . '/';
            Storage::makeDirectory($directoryPath);

            // Criando nome seguro para o arquivo (AGORA COM LETRAS MAIÚSCULAS)
            $uploadName = strtoupper(str_replace(" ", "_", 
                $request->clients_client . '_' . $request->systems_system . '_' . $request->type . '_' . $request->model . '_' . date("dmy_His") . "." . $extension));

            // Salvando o arquivo no storage
            $requestUpload->storeAs($directoryPath, $uploadName, 'local');

            // Salvando as informações no banco
            $file = new Files;
            $file->users_name = $request->users_name;
            $file->clients_client = $request->clients_client;
            $file->systems_system = $request->systems_system;
            $file->type = "CLP";
            $file->sector = "CCO";
            $file->path = $directoryPath;
            $file->file = $uploadName;
            $file->save();
        }

        return redirect('clp_weg')->with('success', 'Upload realizado com sucesso!');
    }
}
