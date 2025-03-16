<?php

namespace App\Http\Controllers\Show;

use App\Models\Files;
use App\Models\User;
use Illuminate\Routing\Controller; // Adicionando a importação da classe Controller

class ShowJsonController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function showJSON($id)
    {
        $file = Files::findOrFail($id);
        $filePath = $file->path . DIRECTORY_SEPARATOR . $file->file;

        if (!file_exists($filePath)) {
            // Retorna para a página anterior em caso de erro
            return back()->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        $jsonContent = file_get_contents($filePath);
        $jsonData = json_decode($jsonContent, true);

        return response()->json($jsonData, 200, [], JSON_PRETTY_PRINT);
    }
}
