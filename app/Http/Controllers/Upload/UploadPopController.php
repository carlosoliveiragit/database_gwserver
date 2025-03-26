<?php

namespace App\Http\Controllers\Upload;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Types;
use App\Models\Sectors;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller
use Illuminate\Support\Str;


class UploadPopController extends Controller
{
    protected $user;

    public function __construct(Users $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function index($sector)
    {
        $Clients = Clients::all();
        $Users = Users::all();
        $Systems = Systems::all();
        $Types = Types::all();
        $Sectors = Sectors::all();

        // Verifica se o setor existe e define a view correspondente
        $viewName = 'uploads.upload_pop.' . $sector . '.index';

        // Verifica se a view do setor existe
        if (!view()->exists($viewName)) {
            abort(404, "Setor não encontrado");
        }

        return view($viewName, compact("sector", "Clients", "Users", "Systems", "Types", "Sectors"));
    }

    public function store(Request $request, $sector)
    {
        $request->validate([
            'upload.*' => 'required|file|mimes:pdf|max:10240',
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
        ]);

        $type_xid = "TP_5FSPEC";//POP
        $types_name = trim(Types::where('xid', 'TP_5FSPEC')->value('name'));//POP

        $viewOrigem = $request->input('view_origem');

        if ($viewOrigem === "cco") {
            $sector_xid = "SC_BFDEPA";//CCO
            $sectors_name = trim(Sectors::where('xid', 'SC_BFDEPA')->value('name'));//CCO
        }
        if ($viewOrigem === "manutencao") {
            $sector_xid = "SC_EX3U73";//MANUTENÇÃO
            $sectors_name = trim(Sectors::where('xid', 'SC_EX3U73')->value('name'));//MANUTENÇÃO
        }

        if ($viewOrigem === "operacao") {
            $sector_xid = "SC_XYIYFC";//OPERAÇÃO
            $sectors_name = trim(Sectors::where('xid', 'SC_XYIYFC')->value('name'));//OPERAÇÃO
        }

        // Validação e sanitização dos campos clients_client, systems_system e sector
        $users_user = $this->sanitizeInput($request->users_user);
        $clients_client = $this->sanitizeInput($request->clients_client);
        $systems_system = $this->sanitizeInput($request->systems_system);
        $type_name = $this->sanitizeInput($types_name);
        $sector_name = $this->sanitizeInput($sectors_name);
        
        if ($request->hasFile('upload')) {
            foreach ($request->file('upload') as $requestUpload) {
                if ($requestUpload->isValid()) {
                    // Nome e extensão formatados
                    $originalName = pathinfo($requestUpload->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = strtolower($requestUpload->getClientOriginalExtension());
                    $uploadName = $sector . ' ' . $originalName;
                    $uploadName = $this->sanitizeInput($uploadName);
                    // Adiciona a extensão em minúsculas
                    $uploadName .= '.' . $extension;

                    // Definir caminho base
                    $baseFilePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);
                    // Criar diretório completo
                    $directoryPath = $baseFilePath .
                        $clients_client . DIRECTORY_SEPARATOR .
                        $systems_system . DIRECTORY_SEPARATOR .
                        $sector_name . DIRECTORY_SEPARATOR .
                        $type_name . DIRECTORY_SEPARATOR;

                    // Caminho completo do arquivo
                    $fullFilePath = $directoryPath . $uploadName;

                    // Criar diretório se não existir
                    if (!File::exists($directoryPath)) {
                        File::makeDirectory($directoryPath, 0777, true);
                    }

                    // Associar as chaves estrangeiras corretamente
                    $user = Users::where('name', $request->users_name)->first();
                    $client = Clients::where('name', $request->clients_client)->first();
                    $system = Systems::where('name', $request->systems_system)->first();
                    
                    // Verificar se arquivo com mesmo nome e caminho já existe no banco
                    $file = Files::where('user_xid', $request->users_name)
                        ->where('client_xid', $request->clients_client)
                        ->where('system_xid', $request->systems_system)
                        ->where('type_xid', $type_xid)
                        ->where('sector_xid', $sector_xid)
                        ->where('file', $uploadName)
                        ->where('path', $directoryPath)
                        ->first();

                    if ($file) {
                        // Se o arquivo físico existir, deletar antes de substituir
                        if (File::exists($fullFilePath)) {
                            File::delete($fullFilePath);
                        }
                        // Atualiza campos se necessário (aqui mantemos setor)
                        $file->save();

                    } else {
                        // Novo registro no banco
                        $file = new Files;
                        $file->user_xid = $user->xid;// Associando o usuário
                        $file->client_xid = $client->xid;// Associando o cliente
                        $file->system_xid = $system->xid;// Associando o sistema
                        $file->type_xid = $type_xid;// Associando o type
                        $file->sector_xid = $sector_xid;// Associando o sector
                        $file->path = $directoryPath;
                        $file->file = $uploadName;
                        $file->save();
                    }

                    // Move o novo arquivo
                    $requestUpload->move($directoryPath, $uploadName);
                }
            }
        }
        // Redireciona para a rota do setor
        return redirect()->back()->with('success', 'Upload realizado com sucesso');
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
