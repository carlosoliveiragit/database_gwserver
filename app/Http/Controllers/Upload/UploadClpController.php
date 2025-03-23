<?php

namespace App\Http\Controllers\Upload;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller
use Illuminate\Support\Str;

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

        return view($viewName, [
            'Clients' => $Clients,
            'Systems' => $Systems,
            'Users' => $Users,
            'model' => $model
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'upload.*' => 'required|file|max:10240',
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'model' => 'required|string', // Adicionando validação para o modelo
        ]);

        // Validação e sanitização dos campos clients_client, systems_system e model
        $clients_client = $this->sanitizeInput($request->clients_client);
        $systems_system = $this->sanitizeInput($request->systems_system);
        $model = $this->sanitizeInput($request->model);

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
            if (!isset($validExtensions[$model]) || $validExtensions[$model] !== $extension) {
                return redirect()->back()
                    ->withInput() // Mantém os dados inseridos no formulário
                    ->with('error', 'O arquivo deve ter a extensão .' . $validExtensions[$model] . ' para o modelo ' . $model);
            }

            // Definição do caminho base (até ARQUIVOS)
            $baseFilePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);

            // Criando o caminho completo com as subpastas
            $directoryPath = $baseFilePath . $clients_client . DIRECTORY_SEPARATOR . $systems_system . DIRECTORY_SEPARATOR . "MANUTENCAO" . DIRECTORY_SEPARATOR . "CLP" . DIRECTORY_SEPARATOR . $model . DIRECTORY_SEPARATOR;

            // Criar o diretório se não existir
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true); // true permite criar subpastas intermediárias
            }

            // Criando nome seguro para o arquivo
            $uploadName = $clients_client . ' ' . $systems_system . ' ' . $request->type_Ident . ' ' . $model . ' ' . date("dmy His");
            // Usa a função de sanitização diretamente para o nome do arquivo
            $uploadName = $this->sanitizeInput($uploadName);

            // Adiciona a extensão em minúsculas
            $uploadName .= '.' . $extension;

            // Salvando o arquivo na pasta mapeada
            $requestUpload->move($directoryPath, $uploadName);

            // Salvando as informações no banco de dados
            $file = new Files;
            $file->users_name = $request->users_name;
            $file->clients_client = $request->clients_client;
            $file->systems_system = $request->systems_system;
            $file->path = $directoryPath;
            $file->file = $uploadName;
            $file->type = "CLP";
            $file->sector = "MANUTENCAO";
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