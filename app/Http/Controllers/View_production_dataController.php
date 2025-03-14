<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Files;
use App\Models\User;

class View_production_dataController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function index()
{
    $Files = Files::where('type', 'LIKE', '%DADOS DE PRODUCAO%')->get();  // Use get() to execute the query
    return view('view_production_data.index', ['Files' => $Files]);
}

    public function destroy($id, Request $request)
    {
        $filePath =  $request->path . DIRECTORY_SEPARATOR . $request->file;

        if (file_exists($filePath)) {
            unlink($filePath);
            Files::findOrFail($id)->delete();
            return redirect('view_production_data')->with('success', 'Arquivo Deletado com Sucesso');
        }

        return redirect('view_production_data')->with('error', 'Arquivo não encontrado.');
    }

    public function download($file)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }

        // Buscar no banco de dados o caminho do arquivo
        $fileEntry = Files::where('file', $file)->first();

        if (!$fileEntry) {
            return redirect()->route('view_production_data')->with('error', 'Arquivo não encontrado no banco de dados.');
        }

        // Montar o caminho completo baseado no registro do banco
        $filePath = $fileEntry->path . DIRECTORY_SEPARATOR . $fileEntry->file;

        if (!file_exists($filePath)) {
            return redirect()->route('view_production_data')->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        return response()->download($filePath);
    }


}
