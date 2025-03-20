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
    ]);

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
        if (!isset($validExtensions[$request->model]) || $validExtensions[$request->model] !== $extension) {
            return redirect()->back()
                ->withInput() // Mantém os dados inseridos no formulário
                ->with('error', 'O arquivo deve ter a extensão .' . $validExtensions[$request->model] . ' para o modelo ' . $request->model);
        }

        // Definição do caminho base (até ARQUIVOS)
        $baseFilePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);

        // Criando o caminho completo com as subpastas
        $directoryPath = $baseFilePath . $request->clients_client . DIRECTORY_SEPARATOR . $request->systems_system . DIRECTORY_SEPARATOR . "MANUTENCAO" . DIRECTORY_SEPARATOR . "CLP" . DIRECTORY_SEPARATOR . $request->model . DIRECTORY_SEPARATOR;

        // Criar o diretório se não existir
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0777, true); // true permite criar subpastas intermediárias
        }

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

}
