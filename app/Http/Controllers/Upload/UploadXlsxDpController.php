<?php
namespace App\Http\Controllers\Upload;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller


class UploadXlsxDpController extends Controller
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

        return view('uploads.upload_xlsx_dp.index', [
            'Clients' => $Clients,
            'Systems' => $Systems,
            'Users' => $Users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'upload.*' => 'required|file|max:10240', // Arquivos até 10MB
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'type' => 'required|string',
        ]);

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

                    $filePath = '\\\\GWSRVFS\\DADOS\\GW BASE EXECUTIVA\\Técnico\\Operação\\CCO\\HOMOLOGACAO\\DADOS DE PRODUCAO\\' . $uploadName;

                    if ($existingFile) {
                        // Atualizar o arquivo existente
                        File::delete($filePath);
                        $requestUpload->move(dirname($filePath), $uploadName);

                        // Atualizar o banco de dados
                        $existingFile->updated_at = now();
                        $existingFile->save();
                    } else {
                        if ($forceUpload) {
                            // Permitir o upload se não houver nenhum arquivo registrado e o botão "Forçar Upload" foi pressionado
                            $requestUpload->move(dirname($filePath), $uploadName);

                            // Salvar novo registro no banco de dados
                            $file = new Files;
                            $file->users_name = $request->users_name;
                            $file->clients_client = $request->clients_client;
                            $file->systems_system = $request->systems_system;
                            $file->type = $request->type;
                            $file->sector = "OPERACAO";
                            $file->path = dirname($filePath);
                            $file->file = $uploadName;
                            $file->save();
                        } else {
                            // Bloquear o upload se o arquivo não estiver registrado e o botão "Forçar Upload" não foi pressionado
                            return redirect('upload_xlsx_dp')->with('error', 'Upload não permitido. Arquivo não registrado no banco de dados.');
                        }
                    }
                }
            }
        }

        return redirect('upload_xlsx_dp')->with('success', 'Upload realizado com sucesso');
    }
}