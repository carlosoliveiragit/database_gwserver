<?php

namespace App\Http\Controllers\Upload;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Types;
use App\Models\Sectors;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller
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
        $Users = Users::all();
        $Clients = Clients::all();
        $Systems = Systems::all();
        $Types = Types::where('xid', 'LIKE', '%TP_EBGROE%')//SCADA
             ->orWhere('xid', 'LIKE', '%TP_RTPNDC%')//NODERED
             ->get();

        return view('uploads.upload_telemetry.index', compact('Clients', 'Users', 'Systems', 'Types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'types_type' => 'required|string',
            'upload' => 'nullable|file|max:2048',
            'json_text' => 'nullable|string'
        ]);

        $sector_xid = "SC_BFDEPA"; // CCO
        $sectors_name = trim(Sectors::where('xid', $sector_xid)->value('name') ?? ''); // CCO
        
        // Validação e sanitização dos campos
        $users_neme = $this->sanitizeInput($request->users_name);
        $clients_name = $this->sanitizeInput($request->clients_client);
        $systems_name = $this->sanitizeInput($request->systems_system);
        $types_name = $this->sanitizeInput($request->types_type);
        $sector_name = $this->sanitizeInput($sectors_name);

        // Associar as chaves estrangeiras corretamente
        $user = Users::where('name', $request->users_name)->first();
        $client = Clients::where('name', $request->clients_client)->first();
        $system = Systems::where('name', $request->systems_system)->first();
        $type = Types::where('name', $request->types_type)->first();
        

        $file = new Files;
        $file->user_xid = $user->xid; // Associando o usuário
        $file->client_xid = $client->xid; // Associando o cliente
        $file->system_xid = $system->xid; // Associando o sistema
        $file->type_xid = $type->xid;// Associando o type
        $file->sector_xid = $sector_xid;

        $basePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);
        $directoryPath = $basePath . 
        $clients_name . DIRECTORY_SEPARATOR . 
        $systems_name . DIRECTORY_SEPARATOR . 
        $sector_name . DIRECTORY_SEPARATOR .
        $types_name . DIRECTORY_SEPARATOR;

        

        // Criar diretório se não existir
        if (!file_exists($directoryPath)) {
            if (!mkdir($directoryPath, 0755, true)) {
                return redirect()->back()->with('error', 'Não foi possível criar o diretório de armazenamento.');
            }
        }

        

        $timestamp = date("dmy-His");

        // Nome seguro para o arquivo
        $uploadNameBase = $clients_name .' '. $systems_name.' '.$sector_name.' '. $types_name.' '. $timestamp;
        $uploadName = $this->sanitizeInput($uploadNameBase) . '.json';

        // Comentário a ser incluído no JSON
        $comment = [
            'GENERAL WATER' => 'CENTRO DE CONTROLE OPERACIONAL',
            'USUARIO' => $users_neme,
            'CLIENTE' => $clients_name ,
            'SISTEMA' =>  $systems_name,
            'SETOR' => $sector_name,
            'TIPO' => $types_name,
            'DATA' => now()->format('d-m-Y H:i:s')
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

            file_put_contents($directoryPath . $uploadName, $jsonContentWithComment);
            $file->file = $uploadName;

        } elseif ($request->json_text) {
            $jsonContent = $request->json_text;

            if (json_decode($jsonContent) === null && json_last_error() !== JSON_ERROR_NONE) {
                return redirect()->back()->withInput()->with('error', 'O JSON inserido não é válido: ' . json_last_error_msg());
            }

            $jsonDecoded = json_decode($jsonContent, true);
            $jsonDecoded['comentario'] = $comment;
            $jsonContentWithComment = json_encode($jsonDecoded, JSON_PRETTY_PRINT);

            file_put_contents($directoryPath . $uploadName, $jsonContentWithComment);
            $file->file = $uploadName;

        } else {
            return redirect()->back()->withInput()->with('error', 'Nenhum JSON enviado.');
        }

        $file->path = $directoryPath;
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