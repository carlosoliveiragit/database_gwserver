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
use Imagick;
use iio\libmergepdf\Merger;

class UploadSetpointsController extends Controller
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

        return view('uploads.upload_setpoints.index', compact('Clients', 'Users', 'Systems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'users_name' => 'required|string',
            'clients_client' => 'required|string',
            'systems_system' => 'required|string',
            'upload.*' => 'nullable|mimes:jpeg,png,jpg|max:4096',
            'uploadPdf.*' => 'nullable|mimes:pdf|max:4096',
        ]);

        $sector_xid = "SC_XYIYFC";
        $sectors_name = trim(Sectors::where('xid', 'SC_XYIYFC')->value('name'));
        $type_xid = "TP_SBTJXF";
        $types_name = trim(Types::where('xid', 'TP_SBTJXF')->value('name'));

        $clients_client = $this->sanitizeInput($request->clients_client);
        $systems_system = $this->sanitizeInput($request->systems_system);
        $sector_name = $this->sanitizeInput($sectors_name);
        $type_name = $this->sanitizeInput($types_name);

        $basePath = Str::finish(config('filesystems.paths.support_files_base'), DIRECTORY_SEPARATOR);
        $directoryPath = $basePath .
            $clients_client . DIRECTORY_SEPARATOR .
            $systems_system . DIRECTORY_SEPARATOR .
            $sector_name . DIRECTORY_SEPARATOR .
            $type_name . DIRECTORY_SEPARATOR;

        if (!$request->hasFile('upload') && !$request->hasFile('uploadPdf')) {
            return redirect()->back()->with('error', 'É necessário enviar ao menos uma imagem ou um arquivo PDF.');
        }

        if (!file_exists($directoryPath)) {
            if (!mkdir($directoryPath, 0755, true)) {
                return redirect()->back()->with('error', 'Não foi possível criar o diretório de armazenamento.');
            }
        }

        $timestamp = date("dmy-His");
        $savedFileNames = [];

        // Converter imagens em PDF
        if ($request->hasFile('upload')) {
            $pdfNameFromImages = "{$clients_client}-{$systems_system}-{$sector_name}-{$type_name}-{$timestamp}.pdf";
            $pdfFullPath = $directoryPath . $pdfNameFromImages;

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

        // Fundir múltiplos PDFs
        if ($request->hasFile('uploadPdf')) {
            $mergedPdfName = "{$clients_client}-{$systems_system}-{$sector_name}-{$type_name}-{$timestamp}.pdf";
            $mergedPdfPath = $directoryPath . $mergedPdfName;

            $merger = new Merger();
            foreach ($request->file('uploadPdf') as $pdfFile) {
                $merger->addFile($pdfFile->getRealPath());
            }

            file_put_contents($mergedPdfPath, $merger->merge());

            $savedFileNames[] = $mergedPdfName;
        }

        // Salvar no banco
        foreach ($savedFileNames as $savedFileName) {
            $user = Users::where('name', $request->users_name)->first();
            $client = Clients::where('name', $request->clients_client)->first();
            $system = Systems::where('name', $request->systems_system)->first();

            $file = new Files();
            $file->user_xid = $user->xid;
            $file->client_xid = $client->xid;
            $file->system_xid = $system->xid;
            $file->type_xid = $type_xid;
            $file->sector_xid = $sector_xid;
            $file->path = $directoryPath;
            $file->file = $savedFileName;
            $file->save();
        }

        return redirect()->back()->with('success', 'Arquivos carregados com sucesso!');
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
