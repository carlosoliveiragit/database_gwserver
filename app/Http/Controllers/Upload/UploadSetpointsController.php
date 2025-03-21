<?php

namespace App\Http\Controllers\Upload;

use App\Models\User;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller
use Illuminate\Support\Str;
use Imagick;


class UploadSetpointsController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function index()
    {
        $Clients = Clients::all();
        $Users = User::all();
        $Systems = Systems::all();

        return view('uploads.upload_setpoints.index', compact('Clients', 'Users', 'Systems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'type' => 'required|string',
            'upload.*' => 'nullable|mimes:jpeg,png,jpg|max:4096',
            'uploadPdf' => 'nullable|mimes:pdf|max:4096',
        ]);

        // Caminho de armazenamento
        $pdfPath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR) . 
        $request->clients_client . DIRECTORY_SEPARATOR . 
        $request->systems_system . DIRECTORY_SEPARATOR . 
        $request->type . DIRECTORY_SEPARATOR;

        // Verificar se o diretório existe, se não, criar
        if (!file_exists($pdfPath)) {
            if (!mkdir($pdfPath, 0777, true)) {
                return redirect()->back()->with('error', 'Não foi possível criar o diretório de armazenamento.');
            }
        }

        $pdfName = strtoupper($request->clients_client . '_' . $request->systems_system . '_' . $request->type . '_' . date("dmy_His")) . '.pdf';
        $pdfFullPath = $pdfPath . DIRECTORY_SEPARATOR . $pdfName;

        // Processamento de imagens para PDF
        if ($request->hasFile('upload')) {
            $imagick = new Imagick();
            foreach ($request->file('upload') as $file) {
                $img = new Imagick($file->getRealPath());
                $img->setImageFormat('pdf');
                $imagick->addImage($img);
            }

            // Criar PDF se houver imagens
            if ($imagick->getNumberImages() > 0) {
                $imagick->writeImages($pdfFullPath, true);
                $imagick->clear();
                $imagick->destroy();
            }
        }

        // Upload de PDF separado
        if ($request->hasFile('uploadPdf')) {
            $pdfFile = $request->file('uploadPdf');
            $extension = strtolower($pdfFile->getClientOriginalExtension());
            $pdfFileName = strtoupper($request->clients_client . '_' . $request->systems_system . '_' . $request->type . '_' . date("dmy_His")) . '.' . $extension;
            $pdfFile->move($pdfPath, $pdfFileName);
        }
        // Salvar no banco de dados
        $fileEntry = new Files();
        $fileEntry->users_name = $request->users_name;
        $fileEntry->clients_client = $request->clients_client;
        $fileEntry->systems_system = $request->systems_system;
        $fileEntry->path = $pdfPath;
        $fileEntry->file = $pdfName;
        $fileEntry->type = $request->type;
        $fileEntry->sector = "OPERACAO";
        $fileEntry->save();

        return redirect('upload_setpoints')->with('success', 'Arquivos carregados com sucesso!');
    }
}
