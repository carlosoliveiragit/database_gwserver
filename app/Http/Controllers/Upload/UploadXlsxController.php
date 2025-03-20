<?php
namespace App\Http\Controllers\Upload;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;


class UploadXlsxController extends Controller
{
    protected $user;

    public function __construct(Users $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function index($type)
    {
        $Clients = Clients::all();
        $Users = Users::all();
        $Systems = Systems::all();

        $viewName = 'uploads.upload_xlsx.' . $type . '.index';

        if (!view()->exists($viewName)) {
            abort(404, "Setor não encontrado");
        }

        return view($viewName, [
            'Clients' => $Clients,
            'Systems' => $Systems,
            'Users' => $Users,
            'type' => $type
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'upload.*' => 'required|file|max:10240', // Arquivos até 10MB
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
        ]);

        // Definir tipo de inserção com verificação
        if ($request->type === 'support_files') {
            $type_insert = 'ARQUIVO DE SUPORTE';
        } elseif ($request->type === 'production_data') {
            $type_insert = 'DADOS DE PRODUCAO';
        } else {
            return redirect()->back()->with('error', 'Tipo de upload inválido.');
        }

        $forceUpload = $request->input('force_upload') === 'true';

        if ($request->hasFile('upload')) {
            foreach ($request->file('upload') as $requestUpload) {
                if ($requestUpload->isValid()) {
                    // Criando nome do arquivo com extensão em minúsculas
                    $originalName = pathinfo($requestUpload->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = strtolower($requestUpload->getClientOriginalExtension()); // Garantir extensão minúscula
                    $uploadName = $originalName . '.' . $extension; // Nome original

                    // Verificar se o arquivo está registrado no banco de dados
                    $existingFile = Files::where('clients_client', $request->clients_client)
                        ->where('systems_system', $request->systems_system)
                        ->where('file', $uploadName)
                        ->first();

                    switch ($request->type) {
                        case 'support_files':
                            $basePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);
                            $filePath = $basePath .
                                $request->clients_client . DIRECTORY_SEPARATOR .
                                $request->systems_system . DIRECTORY_SEPARATOR .
                                $request->sector . DIRECTORY_SEPARATOR . 
                                $type_insert . DIRECTORY_SEPARATOR;
                            break;

                        case 'production_data':
                            $filePath = Str::finish(config('filesystems.paths.production_data_base'), DIRECTORY_SEPARATOR);
                            break;

                        default:
                            return redirect()->back()->with('error', 'Tipo de upload inválido.');
                    }


                    // Verificar se o diretório existe, senão, cria
                    if (!File::exists($filePath)) {
                        // Cria o diretório e os intermediários (subpastas)
                        File::makeDirectory($filePath, 0777, true); // O parâmetro 'true' garante que as pastas intermediárias sejam criadas
                    }

                    if ($existingFile) {
                        // Atualizar o arquivo existente
                        File::delete($filePath . $uploadName);
                        $requestUpload->move($filePath, $uploadName);

                        // Atualizar o banco de dados
                        $existingFile->updated_at = now();
                        $existingFile->save();
                    } else {
                        if ($forceUpload) {
                            // Permitir o upload se não houver nenhum arquivo registrado e o botão "Forçar Upload" foi pressionado
                            $requestUpload->move($filePath, $uploadName);

                            // Salvar novo registro no banco de dados
                            $file = new Files;
                            $file->users_name = $request->users_name;
                            $file->clients_client = $request->clients_client;
                            $file->systems_system = $request->systems_system;
                            $file->path = $filePath;
                            $file->file = $uploadName;
                            $file->type = $type_insert;
                            $file->sector = $request->sector;
                            $file->save();
                        } else {
                            // Bloquear o upload se o arquivo não estiver registrado e o botão "Forçar Upload" não foi pressionado
                            return redirect()->back()->with('error', 'Upload não permitido. Arquivo não registrado no banco de dados.');
                        }
                    }
                }
            }
        }

        //dd($type_insert, $filePath, $uploadName);

        return redirect()->back()->with('success', 'Upload realizado com sucesso');
    }
}
