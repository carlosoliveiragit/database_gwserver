<?php

namespace App\Http\Controllers\Upload;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller

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

        // Verifica se o setor existe e define a view correspondente
        $viewName = 'uploads.upload_pop.' . $sector . '.index';

        // Verifica se a view do setor existe
        if (!view()->exists($viewName)) {
            abort(404, "Setor não encontrado");
        }

        return view($viewName, [
            'Clients' => $Clients,
            'Systems' => $Systems,
            'Users' => $Users,
            'sector' => $sector
        ]);
    }

    public function store(Request $request, $sector)
    {
        $request->validate([
            'upload.*' => 'required|file|mimes:pdf|max:10240',
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
        ]);

        if ($request->hasFile('upload')) {
            foreach ($request->file('upload') as $requestUpload) {
                if ($requestUpload->isValid()) {
                    // Nome e extensão formatados
                    $originalName = pathinfo($requestUpload->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = strtolower($requestUpload->getClientOriginalExtension());
                    $uploadName = strtoupper($sector . "-" . $originalName) . '.' . $extension;

                    // Definir caminho base
                    $baseFilePath = '\\\\GWSRVFS\\DADOS\\GW BASE EXECUTIVA\\Técnico\\Operação\\CCO\\HOMOLOGACAO\\ARQUIVOS\\';

                    // Criar diretório completo
                    $directoryPath = $baseFilePath . $request->clients_client . DIRECTORY_SEPARATOR .
                        $request->systems_system . DIRECTORY_SEPARATOR .
                        strtoupper($sector) . DIRECTORY_SEPARATOR .
                        "POP" . DIRECTORY_SEPARATOR;

                    // Caminho completo do arquivo
                    $fullFilePath = $directoryPath . $uploadName;

                    // Criar diretório se não existir
                    if (!File::exists($directoryPath)) {
                        File::makeDirectory($directoryPath, 0777, true);
                    }

                    // Verificar se arquivo com mesmo nome e caminho já existe no banco
                    $file = Files::where('users_name', $request->users_name)
                        ->where('clients_client', $request->clients_client)
                        ->where('systems_system', $request->systems_system)
                        ->where('type', 'POP') // Tipo sempre POP
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
                        $file->users_name = $request->users_name;
                        $file->clients_client = $request->clients_client;
                        $file->systems_system = $request->systems_system;
                        $file->type = 'POP'; // Tipo sempre POP
                        $file->sector = strtoupper($sector);
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
        return redirect()->route('uploads.upload_pop.index', ['sector' => $sector])->with('success', 'Upload realizado com sucesso');
    }

}
