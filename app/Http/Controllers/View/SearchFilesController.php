<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use App\Models\Types;
use App\Models\Sectors;
use Illuminate\Routing\Controller;

class SearchFilesController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $Clients = Clients::all();
        $Systems = Systems::all();
        $Types = Types::all();
        $Sectors = Sectors::all();
        

        $Files = collect(); // Inicializa a variável files como uma coleção vazia

        // Se houver filtros aplicados, só então buscamos os arquivos
        if ($request->has('clients_client') || $request->has('systems_system') || $request->has('types_type') || $request->has('sectors_sector')) {
            $query = Files::query();

            if ($request->has('clients_client')) {
                $query->where('client_xid', $request->clients_client);
            }
            if ($request->has('systems_system')) {
                $query->where('system_xid', $request->systems_system);
            }
            if ($request->has('types_type')) {
                $query->where('type_xid', $request->types_type);
            }
            if ($request->has('sectors_sector')) {
                $query->where('sector_xid', $request->sectors_sector);
            }
            $Files = $query->with(['user', 'client', 'system', 'type', 'sector'])->get();
        }
        return view('view.search_files.index', compact('Clients', 'Systems', 'Types', 'Sectors', 'Files'));
    }

    public function destroy($id, Request $request)
    {
        $filePath = $request->path . DIRECTORY_SEPARATOR . $request->file;

        if (file_exists($filePath)) {
            unlink($filePath);
            Files::findOrFail($id)->delete();
            return redirect('search_files')->with('success', 'Arquivo Deletado com Sucesso');
        }

        return redirect('search_files')->with('error', 'Arquivo não encontrado.');
    }

    public function download($file)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }

        // Buscar no banco de dados o caminho do arquivo
        $fileEntry = Files::where('file', $file)->first();

        if (!$fileEntry) {
            return redirect()->route('search_files')->with('error', 'Arquivo não encontrado no banco de dados.');
        }

        // Montar o caminho completo baseado no registro do banco
        $filePath = $fileEntry->path . DIRECTORY_SEPARATOR . $fileEntry->file;

        if (!file_exists($filePath)) {
            return redirect()->route('search_files')->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        return response()->download($filePath);
    }
}