<?php
namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                    $file = new Files;

                    $file->users_name = $request->users_name;
                    $file->clients_client = $request->clients_client;
                    $file->systems_system = $request->systems_system;
                    $file->type = $request->type;
                    $file->sector = "OPERACAO";

                    // Definição do caminho do diretório no storage
                    $filePath = 'private/received_file/' . $request->clients_client . '/' . $request->systems_system . '/' . $request->type . '/';
                    Storage::makeDirectory($filePath);

                    $file->path = $filePath;

                    // Criando nome do arquivo: nome em maiúsculas e extensão em minúsculas
                    $originalName = pathinfo($requestUpload->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = strtolower($requestUpload->getClientOriginalExtension()); // Garantir extensão minúscula
                    $uploadName = strtoupper("OPR-".$originalName) . '.' . $extension; // Nome em maiúsculas

                    // Salvando o arquivo no storage
                    $requestUpload->storeAs($filePath, $uploadName, 'local');

                    $file->file = $uploadName;
                    $file->save();
                }
            }
        }

        return redirect('pop_oper_bkp')->with('success', 'Upload realizado com sucesso');
    }
}
