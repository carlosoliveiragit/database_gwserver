<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Models\Files;
use App\Models\User;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller


class FilesController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function index()
    {
        $Files = Files::all();
        return view('view.files.index', ['Files' => $Files]);
    }

    public function destroy($id, Request $request)
    {
        $filePath = $request->path . DIRECTORY_SEPARATOR . $request->file;

        if (file_exists($filePath)) {
            unlink($filePath);
            Files::findOrFail($id)->delete();
            return redirect('files')->with('success', 'Arquivo Deletado com Sucesso');
        }

        return redirect('files')->with('error', 'Arquivo não encontrado.');
    }

    public function download($file)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }

        // Buscar no banco de dados o caminho do arquivo
        $fileEntry = Files::where('file', $file)->first();

        if (!$fileEntry) {
            return redirect()->route('files')->with('error', 'Arquivo não encontrado no banco de dados.');
        }

        // Montar o caminho completo baseado no registro do banco
        $filePath = $fileEntry->path . DIRECTORY_SEPARATOR . $fileEntry->file;

        if (!file_exists($filePath)) {
            return redirect()->route('files')->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        return response()->download($filePath);
    }

}
