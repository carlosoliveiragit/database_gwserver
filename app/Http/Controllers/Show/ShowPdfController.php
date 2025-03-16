<?php

namespace App\Http\Controllers\Show;

use App\Models\Files;
use App\Models\User;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller

class ShowPdfController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function showPDF($id)
    {
        $file = Files::findOrFail($id);
        $filePath = $file->path . DIRECTORY_SEPARATOR . $file->file;

        if (!file_exists($filePath)) {
            // Retorna para a página anterior em caso de erro
            return back()->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file->file . '"'
        ]);
    }
}
