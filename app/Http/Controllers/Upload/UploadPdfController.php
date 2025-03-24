<?php

namespace App\Http\Controllers\Upload;

use App\Models\User;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Sectors;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller
use Illuminate\Support\Str;

use Clegginabox\PDFMerger\PDFMerger;

class UploadPdfController extends Controller
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
        $Sectors = Sectors::all();

        return view('uploads.upload_pdf.index', compact('Clients', 'Users', 'Systems', 'Sectors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'sectors_sector' => 'required|string',
            'uploadPdf.*' => 'nullable|mimes:pdf|max:4096',
        ]);

        $clients_client = $this->sanitizeInput($request->clients_client);
        $systems_system = $this->sanitizeInput($request->systems_system);
        $sector = $this->sanitizeInput($request->sectors_sector);

        $basePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);
        $pdfPath = $basePath . $clients_client . DIRECTORY_SEPARATOR . $systems_system . DIRECTORY_SEPARATOR . $sector . DIRECTORY_SEPARATOR;

        // Validar que pelo menos um arquivo foi enviado
        if (!$request->hasFile('uploadPdf')) {
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

        // Upload de múltiplos PDFs fundidos em um único PDF
        if ($request->hasFile('uploadPdf')) {
            $mergedPdfName = "{$clients_client}-{$systems_system}-{$sector}-{$timestamp}.pdf";
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
            // Associar as chaves estrangeiras corretamente
            $client = Clients::where('name', $request->clients_client)->first();
            $system = Systems::where('name', $request->systems_system)->first();
            $sector = Sectors::where('name', $request->sectors_sector)->first();
            $user   = User   ::where('name', $request->users_name)->first();  

            $fileEntry = new Files();
            $fileEntry->user_xid = $user->xid; // Associando o usuário
            $fileEntry->client_xid = $client->xid; // Associando o cliente
            $fileEntry->system_xid = $system->xid; // Associando o sistema
            $fileEntry->sector_xid = $sector->xid; // Associando o setor
            $fileEntry->path = $pdfPath;
            $fileEntry->file = $savedFileName;
            $fileEntry->type_xid = "TP_I8JYSI";  // Se necessário, você pode armazenar um tipo
            $fileEntry->save();
        }

        return redirect('upload_pdf')->with('success', 'Arquivos carregados com sucesso!');
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
