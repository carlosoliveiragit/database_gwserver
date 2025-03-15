<?php
namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;

class Pop_oper_bkpController extends Controller
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

        return view('uploads.pop_oper_bkp.index', [
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

        if ($request->hasFile('upload')) {
            foreach ($request->file('upload') as $requestUpload) {
                if ($requestUpload->isValid()) {
                    // Criando nome do arquivo: nome em maiúsculas e extensão em minúsculas
                    $originalName = pathinfo($requestUpload->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = strtolower($requestUpload->getClientOriginalExtension()); // Garantir extensão minúscula
                    $uploadName = strtoupper("OPR-" . $originalName) . '.' . $extension; // Nome em maiúsculas

                    // Verificar se o arquivo já existe no banco de dados
                    $file = Files::where('users_name', $request->users_name)
                        ->where('clients_client', $request->clients_client)
                        ->where('systems_system', $request->systems_system)
                        ->where('type', $request->type)
                        ->where('file', $uploadName)
                        ->first();

                    if ($file) {
                        // Atualizar o registro existente
                        $file->sector = "OPERACAO";
                    } else {
                        // Criar um novo registro
                        $file = new Files;
                        $file->users_name = $request->users_name;
                        $file->clients_client = $request->clients_client;
                        $file->systems_system = $request->systems_system;
                        $file->type = $request->type;
                        $file->sector = "OPERACAO";
                        $file->file = $uploadName;
                    }

                    // Definição do caminho do diretório no storage
                    $filePath = '\\\\GWSRVFS\\DADOS\\GW BASE EXECUTIVA\\Técnico\\Operação\\CCO\\HOMOLOGACAO\\ARQUIVOS\\' . $request->clients_client . DIRECTORY_SEPARATOR . $request->systems_system . DIRECTORY_SEPARATOR . $request->type . DIRECTORY_SEPARATOR;

                    $file->path = $filePath;

                    // Salvando o arquivo na pasta mapeada
                    $requestUpload->move($filePath, $uploadName);

                    $file->save();
                }
            }
        }
    return redirect('pop_oper_bkp')->with('success', 'Upload realizado com sucesso');
}
}
