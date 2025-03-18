<?php

namespace App\Http\Controllers\Upload;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller


class UploadClpAltusController extends Controller
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

        return view('uploads.upload_clp_altus.index', compact('Clients', 'Users', 'Systems'));
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

            // Definição do caminho base (até ARQUIVOS)
            $baseFilePath = '\\\\GWSRVFS\\DADOS\\GW BASE EXECUTIVA\\Técnico\\Operação\\CCO\\HOMOLOGACAO\\ARQUIVOS\\';

            // Criando o caminho completo com as subpastas
            $directoryPath = $baseFilePath . $request->clients_client . DIRECTORY_SEPARATOR . $request->systems_system . DIRECTORY_SEPARATOR . "MANUTENCAO" . DIRECTORY_SEPARATOR . "CLP" . DIRECTORY_SEPARATOR . $request->model . DIRECTORY_SEPARATOR;

            // Criar o diretório se não existir
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true); // true permite criar subpastas intermediárias


                // Criando nome seguro para o arquivo
                $uploadName = strtoupper(str_replace(
                    [" - ", "-", " "],
                    "_",
                    $request->clients_client . '_' . $request->systems_system . '_' . $request->type_Ident . '_' . $request->model . '_' . date("dmy_His")
                ));

                // Adiciona a extensão em minúsculas
                $uploadName .= '.' . $extension;

                // Salvando o arquivo na pasta mapeada
                $requestUpload->move($directoryPath, $uploadName);

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


            return redirect('upload_clp_altus')->with('success', 'Upload realizado com sucesso!');
        }
    }
}