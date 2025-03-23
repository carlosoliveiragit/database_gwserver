<?php

namespace App\Http\Controllers\Upload;

use App\Models\User;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Imagick;
use Clegginabox\PDFMerger\PDFMerger;

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
            'uploadPdf.*' => 'nullable|mimes:pdf|max:4096', // Permitir múltiplos PDFs
        ]);

        $clients_client = $this->sanitizeInput($request->clients_client);
        $systems_system = $this->sanitizeInput($request->systems_system);
        $type = $this->sanitizeInput($request->type);

        $basePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);
        $pdfPath = $basePath . $clients_client . DIRECTORY_SEPARATOR . $systems_system . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR;

        // Validar que pelo menos um arquivo foi enviado
        if (!$request->hasFile('upload') && !$request->hasFile('uploadPdf')) {
            return redirect()->back()->with('error', 'É necessário enviar ao menos uma imagem ou um arquivo PDF.');
        }

        // Criar diretório se não existir
        if (!file_exists($pdfPath)) {
            if (!mkdir($pdfPath, 0755, true)) {
                return redirect()->back()->with('error', 'Não foi possível criar o diretório de armazenamento.');
            }
        }

        $timestamp = date("dmy-His");
        $savedFileNames = [];

        // Processamento das imagens -> PDF
        if ($request->hasFile('upload')) {
            $pdfNameFromImages = "{$clients_client}-{$systems_system}-{$type}-{$timestamp}.pdf";
            $pdfFullPath = $pdfPath . $pdfNameFromImages;

            $imagick = new Imagick();
            foreach ($request->file('upload') as $file) {
                $img = new Imagick($file->getRealPath());
                $img->setImageFormat('pdf');
                $imagick->addImage($img);
                $img->clear();
                $img->destroy();
            }

            if ($imagick->getNumberImages() > 0) {
                $imagick->writeImages($pdfFullPath, true);
                $savedFileNames[] = $pdfNameFromImages;
                $imagick->clear();
                $imagick->destroy();
            }
        }

        // Upload de múltiplos PDFs fundidos em um único PDF
        if ($request->hasFile('uploadPdf')) {
            $mergedPdfName = "{$clients_client}-{$systems_system}-{$type}-MERGEDPDF-{$timestamp}.pdf";
            $mergedPdfPath = $pdfPath . $mergedPdfName;

            $pdfMerger = new PDFMerger();

            foreach ($request->file('uploadPdf') as $pdfFile) {
                // Adiciona cada PDF enviado
                $pdfMerger->addPDF($pdfFile->getRealPath(), 'all');
            }

            // Realiza a fusão dos PDFs
            $pdfMerger->merge('file', $mergedPdfPath);

            $savedFileNames[] = $mergedPdfName;
        }

        // Salvar no banco cada arquivo gerado
        foreach ($savedFileNames as $savedFileName) {
            $fileEntry = new Files();
            $fileEntry->users_name = $request->users_name;
            $fileEntry->clients_client = $request->clients_client;
            $fileEntry->systems_system = $request->systems_system;
            $fileEntry->path = $pdfPath;
            $fileEntry->file = $savedFileName;
            $fileEntry->type = $type;
            $fileEntry->sector = "OPERACAO"; // Ajuste conforme necessário
            $fileEntry->save();
        }

        return redirect('upload_setpoints')->with('success', 'Arquivos carregados com sucesso!');
    }

    // Função para sanitizar o input
    private function sanitizeInput($input)
    {
        $input = iconv('UTF-8', 'ASCII//TRANSLIT', $input);
        $input = preg_replace('/[\s_]+/', '-', $input);
        $input = preg_replace('/[^A-Za-z0-9\-]/', '', $input);
        $input = preg_replace('/-+/', '-', $input);
        return strtoupper($input);
    }
}
