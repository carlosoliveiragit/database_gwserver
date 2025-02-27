<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Imagick;

class Images_bkpController extends Controller
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
        $Users = User::all(); // Ajustado para a Model correta
        $Systems = Systems::all();

        return view('uploads.images_bkp.index', compact('Clients', 'Users', 'Systems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'type' => 'required|string',
            'upload.*' => 'nullable|mimes:jpeg,png,jpg|max:4096', // Ajustado para permitir envio opcional
            'uploadPdf' => 'nullable|mimes:pdf|max:4096', // Adicionado suporte para PDFs separados
        ]);

        // Definição do caminho onde os arquivos serão salvos em storage/app/private
        $pdfPath = 'private/received_file/' . $request->clients_client . '/' . $request->systems_system . '/' . $request->type . '/';

        // Garante que o diretório exista dentro de storage/app
        $storagePath = storage_path('app/' . $pdfPath);
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true); // Cria os diretórios necessários
        }

        $pdfName = $request->clients_client . '_' . $request->systems_system . '_' . $request->type . '_' . date("dmy_His") . '.pdf';
        $pdfFullPath = $storagePath . $pdfName;

        $imagick = new Imagick();
        $hasImages = false;

        // Processamento de imagens (upload)
        if ($request->hasFile('upload')) {
            foreach ($request->file('upload') as $file) {
                $img = new Imagick($file->getRealPath());
                $img->setImageFormat('pdf');
                $imagick->addImage($img);
                $hasImages = true;
            }
        }

        // Se houver imagens, cria o PDF
        if ($hasImages) {
            $imagick->writeImages($pdfFullPath, true);
            $imagick->clear();
            $imagick->destroy();
        }

        // Processamento de PDF separado (uploadPdf)
        if ($request->hasFile('uploadPdf')) {
            $pdfFile = $request->file('uploadPdf');
            $pdfFileName = $request->clients_client . '_' . $request->systems_system . '_' . $request->type . '_' . date("dmy_His") . '.pdf';
            $pdfFile->move($storagePath, $pdfFileName);
        }

        // Salvar no banco de dados
        $fileEntry = new Files();
        $fileEntry->users_name = $request->users_name;
        $fileEntry->clients_client = $request->clients_client;
        $fileEntry->systems_system = $request->systems_system;
        $fileEntry->path = $pdfPath; // Caminho sem public
        $fileEntry->file = $pdfName;
        $fileEntry->type = $request->type;
        $fileEntry->sector = "OPERACAO";
        $fileEntry->save();

        return redirect('images_bkp')->with('success', 'Arquivos carregados com sucesso!');
    }


}
