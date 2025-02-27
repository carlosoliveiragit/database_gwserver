<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Files;
use App\Models\User;

class Pop_FilesController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function index()
{
    $Files = Files::where('type', 'LIKE', '%POP%')->get();  // Use get() to execute the query
    return view('pop_files.index', ['Files' => $Files]);
}

    public function destroy($id, Request $request)
    {
        $filePath = storage_path('app/' . $request->path . $request->file);

        if (file_exists($filePath)) {
            unlink($filePath);
            Files::findOrFail($id)->delete();
            return redirect('pop_files')->with('success', 'Arquivo Deletado com Sucesso');
        }

        return redirect('pop_files')->with('error', 'Arquivo não encontrado.');
    }

    public function download($file)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }

        // Buscar no banco de dados o caminho do arquivo
        $fileEntry = Files::where('file', $file)->first();

        if (!$fileEntry) {
            return redirect()->route('pop_files')->with('error', 'Arquivo não encontrado no banco de dados.');
        }

        // Montar o caminho completo baseado no registro do banco
        $filePath = storage_path("app/" . $fileEntry->path . $fileEntry->file);

        if (!file_exists($filePath)) {
            return redirect()->route('pop_files')->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        return response()->download($filePath);
    }


}
