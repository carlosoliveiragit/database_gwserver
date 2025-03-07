<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;

class Search_production_dataController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $Clients = Clients::all();
        $Systems = Systems::all();

        $Files = collect(); // Inicializa a variável Files como uma coleção vazia

        // Se houver filtros aplicados, só então buscamos os arquivos
        if ($request->has('clients_client') || $request->has('systems_system')) {
            $query = Files::query();

            if ($request->has('clients_client')) {
                $query->where('clients_client', $request->clients_client);
            }
            if ($request->has('systems_system')) {
                $query->where('systems_system', $request->systems_system);
            }
            

            // Filtra registros onde type CONTÉM "DADOS DE PRODUCAO"
            $query->where('type', 'LIKE', '%DADOS DE PRODUCAO%');

            $Files = $query->get();
        }

        return view('search_production_data.index', compact('Clients', 'Systems', 'Files'));
    }

    public function destroy($id, Request $request)
    {
        $filePath = storage_path('app/' . $request->path . $request->file);

        if (file_exists($filePath)) {
            unlink($filePath);
            Files::findOrFail($id)->delete();
            return redirect('search_production_data')->with('success', 'Arquivo Deletado com Sucesso');
        }

        return redirect('search_production_data')->with('success', 'Arquivo Deletado com Sucesso');
    }

    public function download($file)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }

        // Buscar no banco de dados o caminho do arquivo
        $fileEntry = Files::where('file', $file)->first();

        if (!$fileEntry) {
            return redirect()->route('search_production_data')->with('error', 'Arquivo não encontrado no banco de dados.');
        }

        // Montar o caminho completo baseado no registro do banco
        $filePath = storage_path("app/" . $fileEntry->path . $fileEntry->file);

        if (!file_exists($filePath)) {
            return redirect()->route('search_production_data')->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        return response()->download($filePath);
    }
}
