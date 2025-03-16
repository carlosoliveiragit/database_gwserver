<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadTelemetryController extends Controller
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

        return view('uploads.upload_telemetry.index', compact('Clients', 'Users', 'Systems'));
    }

    public function store(Request $request)
{
    $request->validate([
        'users_name' => 'required|string',
        'clients_client' => 'required|string',
        'systems_system' => 'required|string',
        'type' => 'required|string',
        'upload' => 'nullable|file|max:2048', // Removemos a validação de tipo de arquivo aqui
        'json_text' => 'nullable|string'
    ]);

    $file = new Files;
    $file->users_name = $request->users_name;
    $file->clients_client = $request->clients_client;
    $file->systems_system = $request->systems_system;
    $file->type = $request->type;
    $file->sector = "CCO";

    // Definição do caminho do diretório no storage
    $filePath = '\\\\GWSRVFS\\DADOS\\GW BASE EXECUTIVA\\Técnico\\Operação\\CCO\\HOMOLOGACAO\\ARQUIVOS\\' . $request->clients_client . DIRECTORY_SEPARATOR . $request->systems_system . DIRECTORY_SEPARATOR . $request->type . DIRECTORY_SEPARATOR;

    if ($request->hasFile('upload') && $request->file('upload')->isValid()) {
        $requestUpload = $request->file('upload');
        $extension = strtolower($requestUpload->getClientOriginalExtension());

        // Validação manual da extensão
        if ($extension !== 'json') {
            return redirect()->back()->withInput()->with('error', 'O arquivo deve ter a extensão .json.');
        }

        // Validar se o conteúdo do arquivo é um JSON válido
        $jsonContent = file_get_contents($requestUpload->getRealPath());
        if (json_decode($jsonContent) === null && json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->withInput()->with('error', 'O arquivo JSON enviado não é válido: ' . json_last_error_msg());
        }

        // Criando nome seguro para o arquivo
        $uploadName = strtoupper(str_replace(
            [" - ", "-", " "],
            "_",
            $request->clients_client . '_' . $request->systems_system . '_' . $request->type . '_' . date("dmy_His")
        )) . '.' . $extension;

        // Salvando o arquivo na pasta mapeada
        $requestUpload->move($filePath, $uploadName);
        $file->file = $uploadName;
    } elseif ($request->json_text) {
        if (json_decode($request->json_text) === null && json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()->withInput()->with('error', 'O JSON inserido não é válido: ' . json_last_error_msg());
        }

        $uploadName = strtoupper(str_replace(
            [" - ", "-", " "],
            "_",
            $request->clients_client . '_' . $request->systems_system . '_' . $request->type . '_' . date("dmy_His")
        )) . '.json';

        // Salvando o JSON colado na pasta mapeada
        file_put_contents($filePath . $uploadName, $request->json_text);
        $file->file = $uploadName;
    } else {
        return redirect()->back()->withInput()->with('error', 'Nenhum JSON enviado.');
    }

    $file->path = $filePath;
    $file->save();

    return redirect('upload_telemetry.index')->with('success', 'Upload realizado com sucesso!');
}
}