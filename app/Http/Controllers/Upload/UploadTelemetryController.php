<?php

namespace App\Http\Controllers\Upload;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

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
            'upload' => 'nullable|file|max:2048',
            'json_text' => 'nullable|string'
        ]);

        $clients_client = $this->sanitizeInput($request->clients_client);
        $systems_system = $this->sanitizeInput($request->systems_system);
        $type = $this->sanitizeInput($request->type);

        $file = new Files;
        $file->users_name = $request->users_name;
        $file->clients_client = $clients_client;
        $file->systems_system = $systems_system;
        $file->type = $type;
        $file->sector = "CCO";

        $filePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR) .
            $clients_client . DIRECTORY_SEPARATOR .
            $systems_system . DIRECTORY_SEPARATOR .
            $type . DIRECTORY_SEPARATOR;

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        // Nome seguro para o arquivo
        $uploadNameBase = $clients_client . ' ' . $systems_system . ' ' . $type . ' ' . date("dmy His");
        $uploadName = $this->sanitizeInput($uploadNameBase) . '.json';

        // Comentário a ser incluído no JSON
        $comment = [
            'GENERAL WATER' => 'CENTRO DE CONTROLE OPERACIONAL',
            'USUARIO' => $request->users_name,
            'CLIENTE' => $clients_client,
            'SISTEMA' => $systems_system,
            'TIPO' => $type,
            'DATA' => now()->format('Y-m-d H:i:s')
        ];

        if ($request->hasFile('upload') && $request->file('upload')->isValid()) {
            $requestUpload = $request->file('upload');
            $extension = strtolower($requestUpload->getClientOriginalExtension());

            if ($extension !== 'json') {
                return redirect()->back()->withInput()->with('error', 'O arquivo deve ter a extensão .json.');
            }

            $jsonContent = file_get_contents($requestUpload->getRealPath());
            if (json_decode($jsonContent) === null && json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()->withInput()->with('error', 'O arquivo JSON enviado não é válido: ' . json_last_error_msg());
            }

            $jsonDecoded = json_decode($jsonContent, true);
            $jsonDecoded['comentario'] = $comment;
            $jsonContentWithComment = json_encode($jsonDecoded, JSON_PRETTY_PRINT);

            file_put_contents($filePath . $uploadName, $jsonContentWithComment);
            $file->file = $uploadName;

        } elseif ($request->json_text) {
            $jsonContent = $request->json_text;

            if (json_decode($jsonContent) === null && json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()->withInput()->with('error', 'O JSON inserido não é válido: ' . json_last_error_msg());
            }

            $jsonDecoded = json_decode($jsonContent, true);
            $jsonDecoded['comentario'] = $comment;
            $jsonContentWithComment = json_encode($jsonDecoded, JSON_PRETTY_PRINT);

            file_put_contents($filePath . $uploadName, $jsonContentWithComment);
            $file->file = $uploadName;

        } else {
            return redirect()->back()->withInput()->with('error', 'Nenhum JSON enviado.');
        }

        $file->path = $filePath;
        $file->save();

        return redirect('upload_telemetry')->with('success', 'Upload realizado com sucesso!');
    }

    // Função para sanitizar o input
    private function sanitizeInput($input)
    {
        // Remove acentos e substitui Ç corretamente usando iconv
        $input = iconv('UTF-8', 'ASCII//TRANSLIT', $input);
        // Substitui underscores e espaços por hífen
        $input = preg_replace('/[\s_]+/', '-', $input);
        // Remove caracteres que não são letras, números ou hífen
        $input = preg_replace('/[^A-Za-z0-9\-]/', '', $input);
        // Substitui múltiplos hífens por um único hífen
        $input = preg_replace('/-+/', '-', $input);
        // Converte para maiúsculas
        return strtoupper($input);
    }

}