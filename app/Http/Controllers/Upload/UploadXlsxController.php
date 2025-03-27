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
        $Sectors_all = Sectors::all();
        $Sectors_filt = Sectors::where('xid', 'LIKE', '%SC_XYIYFC%')->get();//OPERAÇÃO

        // Verifica se o setor existe e define a view correspondente
        $viewName = 'uploads.upload_xlsx.' . $type . '.index';

        // Verifica se a view do setor existe
        if (!view()->exists($viewName)) {
            abort(404, "tipo não encontrado");
        }

        return view($viewName, compact("type", "Clients", "Users", "Systems", "Sectors_all","Sectors_filt"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'upload.*' => 'required|file|max:10240', // Arquivos até 10MB
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'sectors_sector' => 'required|string',
        ]);

        // Associar as chaves estrangeiras corretamente
        $user = Users::where('name', $request->users_name)->first();
        $client = Clients::where('name', $request->clients_client)->first();
        $system = Systems::where('name', $request->systems_system)->first();
        $sector = Sectors::where('name', $request->sectors_sector)->first();

        //verifica blade de origem
        $viewOrigem = $request->input('view_origem');
        
        if ($viewOrigem === "support_files") {
            $sector_xid = $sector->xid;
            $sectors_name = $sector->name;
            $type_xid = "TP_I8JYSI";//ARQUIVO DE APOIO
        }
        if ($viewOrigem === "production_data") {
            $sector_xid = $sector->xid;
            $sectors_name = $sector->name;
            $type_xid = "TP_THKD7H";//DADO DE PRODUÇÃO
        }

        $type = Types::where('xid', $type_xid)->first();

        // Validação e sanitização dos campos
        $user_neme = $this->sanitizeInput($request->users_name);
        $client_name = $this->sanitizeInput($request->clients_client);
        $system_name = $this->sanitizeInput($request->systems_system);
        $sector_name = $this->sanitizeInput($sectors_name);
        $type_name = $this->sanitizeInput($type->name);

        

        $forceUpload = $request->input('force_upload') === 'true';

        if ($request->hasFile('upload')) {
            foreach ($request->file('upload') as $requestUpload) {
                if ($requestUpload->isValid()) {
                    // Criando nome do arquivo com extensão em minúsculas
                    $originalName = pathinfo($requestUpload->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = strtolower($requestUpload->getClientOriginalExtension()); // Garantir extensão minúscula
                    $uploadName = $originalName . '.' . $extension; // Nome original

                    // Verificar se o arquivo está registrado no banco de dados
                    $existingFile = Files::where('client_xid', $client->xid)
                        ->where('system_xid', $system->xid)
                        ->where('type_xid', $type->xid)
                        ->where('file', $uploadName)
                        ->first();

                        //dd($request->type,$client->xid,$system->xid,$type->xid,$uploadName);

                    switch ($request->type) {
                        case 'support_files':
                            $basePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);
                            $filePath = $basePath .
                            $client_name . DIRECTORY_SEPARATOR .
                            $system_name . DIRECTORY_SEPARATOR .
                            $sector_name . DIRECTORY_SEPARATOR . 
                            $type_name . DIRECTORY_SEPARATOR;
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
                        $existingFile->user_xid= $user->xid;
                        $existingFile->updated_at = now();
                        $existingFile->save();

                    } else {
                        if ($forceUpload) {
                            // Permitir o upload se não houver nenhum arquivo registrado e o botão "Forçar Upload" foi pressionado
                            $requestUpload->move($filePath, $uploadName);

                            // Salvar novo registro no banco de dados
                            $file = new Files;
                            $file->user_xid = $user->xid;// Associando o usuário
                            $file->client_xid = $client->xid;// Associando o cliente
                            $file->system_xid = $system->xid;// Associando o sistema
                            $file->type_xid = $type->xid;// Associando o type
                            $file->sector_xid = $sector_xid;// Associando o sector
                            $file->path = $filePath;
                            $file->file = $uploadName;
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
