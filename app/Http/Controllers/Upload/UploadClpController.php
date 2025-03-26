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
use Illuminate\Support\Facades\File;

class UploadClpController extends Controller
{
    protected $user;

    public function __construct(Users $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function index($model)
    {
        $Clients = Clients::all();
        $Users = Users::all();
        $Systems = Systems::all();

        // Verifica se o setor existe e define a view correspondente
        $viewName = 'uploads.upload_clp.' . $model . '.index';

        // Verifica se a view do setor existe
        if (!view()->exists($viewName)) {
            abort(404, "Setor não encontrado");
        }
        return view($viewName, compact("Clients","Systems","Users","model"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'upload.*' => 'required|file|max:10240',
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'models_model' => 'required|string', // Adicionando validação para o modelo
        ]);

        $sector_xid = "SC_EX3U73";//MANUTENÇÃO
        $sectors_name = trim(Sectors::where('xid', 'SC_EX3U73')->value('name'));//CLP
        $type_xid = "TP_S4SA7G";//CLP
        $types_name = trim(Types::where('xid', 'TP_S4SA7G')->value('name'));//CLP

        // Validação e sanitização dos campos clients_client, systems_system e sector
        $users_user = $this->sanitizeInput($request->users_user);
        $clients_client = $this->sanitizeInput($request->clients_client);
        $systems_system = $this->sanitizeInput($request->systems_system);
        $model_ident = $this->sanitizeInput($request->models_ident);
        $sector_name = $this->sanitizeInput($sectors_name);
        $type_name = $this->sanitizeInput($types_name);

        //dd($sector_name);

        $models_model = $this->sanitizeInput($request->models_model);

        if ($request->hasFile('upload') && $request->file('upload')->isValid()) {
            $requestUpload = $request->file('upload');
            $extension = strtolower($requestUpload->getClientOriginalExtension());

            // Mapeamento de modelos e suas extensões válidas
            $validExtensions = [
                'CLIC02' => 'cli',
                'PLC300' => 'bkp',
                'PM5052-T-ETH' => 'projectarchive',
                'PM5032-T-ETH' => 'projectarchive',
                'XP315' => 'projectarchive',
                'XP325' => 'projectarchive',
                'XP340' => 'projectarchive',
                'PLC500' => 'projectarchive',
            ];

            

            // Verifica se o modelo existe no mapeamento e se a extensão é válida
            if (!isset($validExtensions[$models_model]) || $validExtensions[$models_model] !== $extension) {
                return redirect()->back()
                    ->withInput() // Mantém os dados inseridos no formulário
                    ->with('error', 'O arquivo deve ter a extensão .' . $validExtensions[$models_model] . ' para o modelo ' . $models_model);
            }

            // Definição do caminho base (até ARQUIVOS)
            $baseFilePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);
            // Criando o caminho completo com as subpastas
            $directoryPath = $baseFilePath .
                $clients_client . DIRECTORY_SEPARATOR .
                $systems_system . DIRECTORY_SEPARATOR .
                $sector_name . DIRECTORY_SEPARATOR .
                $type_name  . '-' .
                $model_ident . '-' .
                $models_model . DIRECTORY_SEPARATOR;
               

            // Criar o diretório se não existir
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true); // true permite criar subpastas intermediárias
            }

            // Criando nome seguro para o arquivo
            $savedFileName = 
            $clients_client.' '. 
            $systems_system .' '.
            $sector_name.' '. 
            $type_name.' '. 
            $model_ident.' '.
            $models_model.' '. 
            date("dmy His");

            // Usa a função de sanitização diretamente para o nome do arquivo
            $savedFileName = $this->sanitizeInput($savedFileName);

            // Adiciona a extensão em minúsculas
            $savedFileName .= '.' . $extension;

            // Salvando o arquivo na pasta mapeada
            $requestUpload->move($directoryPath, $savedFileName);

            // Associar as chaves estrangeiras corretamente
            $user = Users::where('name', $request->users_name)->first();
            $client = Clients::where('name', $request->clients_client)->first();
            $system = Systems::where('name', $request->systems_system)->first();

            // Salvando as informações no banco de dados
            $file = new Files();
            $file->user_xid = $user->xid; // Associando o usuário
            $file->client_xid = $client->xid; // Associando o cliente
            $file->system_xid = $system->xid; // Associando o sistema
            $file->type_xid = $type_xid; // Associando o type
            $file->sector_xid = $sector_xid; // Associando o setor
            $file->path = $directoryPath;
            $file->file = $savedFileName;
            $file->save(); // Salva as informações no banco de dados após o upload do arquivo
        }

        // Redireciona de volta para a página de onde veio (com sucesso)
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