<?php

namespace App\Http\Controllers\Upload;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Types;
use App\Models\Sectors;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller
use Illuminate\Support\Str;

use Clegginabox\PDFMerger\PDFMerger;

class UploadPdfController extends Controller
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
        $Types = Types::all();
        $Sectors = Sectors::all();

        return view('uploads.upload_pdf.index', compact('Clients', 'Systems', 'Types', 'Users', 'Sectors'));
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

        // Validação e sanitização dos campos clients_client, systems_system e sector
        $users_user = $this->sanitizeInput($request->users_user);
        $clients_client = $this->sanitizeInput($request->clients_client);
        $systems_system = $this->sanitizeInput($request->systems_system);
        $types_type = $this->sanitizeInput($request->types_type);
        $sectors_sector = $this->sanitizeInput($request->sectors_sector);

        $type_xid = "TP_I8JYSI";//ARQUIVO DE APOIO

        // Definir caminho base
        $baseFilePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);
        // Criar diretório completo
        $directoryPath = $baseFilePath .
            $clients_client . DIRECTORY_SEPARATOR .
            $systems_system . DIRECTORY_SEPARATOR .
            $sectors_sector . DIRECTORY_SEPARATOR .
            $types_type . DIRECTORY_SEPARATOR;

        // Validar que pelo menos um arquivo foi enviado
        if (!$request->hasFile('uploadPdf')) {
            return redirect()->back()->with('error', 'É necessário enviar ao menos uma imagem ou um arquivo PDF.');
        }

        // Criar diretório se não existir
        if (!file_exists($directoryPath)) {
            if (!mkdir($directoryPath, 0755, true)) {
                return redirect()->back()->with('error', 'Não foi possível criar o diretório de armazenamento.');
            }
        }

        $timestamp = date("dmy-His");
        $savedFileNames = [];

        // Upload de múltiplos PDFs fundidos em um único PDF
        if ($request->hasFile('uploadPdf')) {
            $mergedPdfName = "{$clients_client}-{$systems_system}-{$sectors_sector}-{$timestamp}.pdf";
            $mergedPdfPath = $directoryPath . $mergedPdfName;

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
            $user = Users::where('name', $request->users_name)->first();
            $client = Clients::where('name', $request->clients_client)->first();
            $system = Systems::where('name', $request->systems_system)->first();
            $type = Types::where('name', $request->types_type)->first();
            $sector = Sectors::where('name', $request->sectors_sector)->first();
             
            $file = new Files();
            $file->user_xid = $user->xid; // Associando o usuário
            $file->client_xid = $client->xid; // Associando o cliente
            $file->system_xid = $system->xid; // Associando o sistema
            $file->type_xid = $type_xid; // Associando o type
            $file->sector_xid = $sector->xid; // Associando o setor
            $file->path = $directoryPath;
            $file->file = $savedFileName;
            $file->save();
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
