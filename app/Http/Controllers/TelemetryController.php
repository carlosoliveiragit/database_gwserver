<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TelemetryController extends Controller
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

        return view('uploads.telemetry.index', compact('Clients', 'Users', 'Systems'));
    }

    public function store(Request $request)
    {
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

        $directoryPath = 'private/received_file/' . $request->clients_client . '/' . $request->systems_system . '/' . $request->type . '/';
        Storage::makeDirectory($directoryPath);

        if ($request->hasFile('upload') && $request->file('upload')->isValid()) {
            $requestUpload = $request->file('upload');
            $extension = strtolower($requestUpload->getClientOriginalExtension());

            // Validação manual da extensão
            if ($extension !== 'json') {
                return redirect()->back()->withInput()->with('error', 'O arquivo deve ter a extensão .json.');
            }

            // Criando nome seguro para o arquivo
            $uploadName = strtoupper(str_replace(
                [" - ", "-", " "],
                "_",
                $request->clients_client . '_' . $request->systems_system . '_' . $request->type . '_' . date("dmy_His")
            ));

            // Adiciona a extensão em minúsculas
            $uploadName .= '.' . $extension;

            // Salvando o arquivo no storage
            $requestUpload->storeAs($directoryPath, $uploadName, 'local');
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

            Storage::put($directoryPath . $uploadName, $request->json_text);
            $file->file = $uploadName;
        } else {
            return redirect()->back()->withInput()->with('error', 'Nenhum JSON enviado.');
        }

        $file->path = $directoryPath;
        $file->save();

        return redirect('telemetry')->with('success', 'Upload realizado com sucesso!');
    }
}