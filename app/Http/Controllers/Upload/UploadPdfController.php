<?php

namespace App\Http\Controllers\Upload;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Types;
use App\Models\Sectors;
use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use iio\libmergepdf\Merger;

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

        $users_user = $this->sanitizeInput($request->users_user);
        $clients_client = $this->sanitizeInput($request->clients_client);
        $systems_system = $this->sanitizeInput($request->systems_system);
        $types_type = $this->sanitizeInput($request->types_type);
        $sectors_sector = $this->sanitizeInput($request->sectors_sector);

        $type_xid = "TP_I8JYSI";
        
        $baseFilePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);
        $directoryPath = $baseFilePath .
            $clients_client . DIRECTORY_SEPARATOR .
            $systems_system . DIRECTORY_SEPARATOR .
            $sectors_sector . DIRECTORY_SEPARATOR .
            $types_type . DIRECTORY_SEPARATOR;

        if (!$request->hasFile('uploadPdf')) {
            return redirect()->back()->with('error', 'É necessário enviar ao menos uma imagem ou um arquivo PDF.');
        }

        if (!file_exists($directoryPath)) {
            if (!mkdir($directoryPath, 0755, true)) {
                return redirect()->back()->with('error', 'Não foi possível criar o diretório de armazenamento.');
            }
        }

        $timestamp = date("dmy-His");
        $savedFileNames = [];

        if ($request->hasFile('uploadPdf')) {
            $mergedPdfName = "{$clients_client}-{$systems_system}-{$sectors_sector}-{$timestamp}.pdf";
            $mergedPdfPath = $directoryPath . $mergedPdfName;

            $merger = new Merger();

            foreach ($request->file('uploadPdf') as $pdfFile) {
                $merger->addFile($pdfFile->getRealPath());
            }

            file_put_contents($mergedPdfPath, $merger->merge());
            $savedFileNames[] = $mergedPdfName;
        }

        foreach ($savedFileNames as $savedFileName) {
            $user = Users::where('name', $request->users_name)->first();
            $client = Clients::where('name', $request->clients_client)->first();
            $system = Systems::where('name', $request->systems_system)->first();
            $type = Types::where('name', $request->types_type)->first();
            $sector = Sectors::where('name', $request->sectors_sector)->first();

            $file = new Files();
            $file->user_xid = $user->xid;
            $file->client_xid = $client->xid;
            $file->system_xid = $system->xid;
            $file->type_xid = $type_xid;
            $file->sector_xid = $sector->xid;
            $file->path = $directoryPath;
            $file->file = $savedFileName;
            $file->save();
        }

        return redirect('upload_pdf')->with('success', 'Arquivos carregados com sucesso!');
    }

    private function sanitizeInput($input)
    {
        $input = iconv('UTF-8', 'ASCII//TRANSLIT', $input);
        $input = preg_replace('/[\s_]+/', '-', $input);
        $input = preg_replace('/[^A-Za-z0-9\-]/', '', $input);
        $input = preg_replace('/-+/', '-', $input);
        return strtoupper($input);
    }
}
