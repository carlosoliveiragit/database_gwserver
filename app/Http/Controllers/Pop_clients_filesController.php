<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;
use PDF;

class Pop_clients_filesController extends Controller
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
        if ($request->has('clients_client') || $request->has('systems_system') || $request->has('types_type') || $request->has('sectors_sector')) {
            $query = Files::query();

            if ($request->has('clients_client')) {
                $query->where('clients_client', $request->clients_client);
            }
            if ($request->has('systems_system')) {
                $query->where('systems_system', $request->systems_system);
            }
            if ($request->has('sectors_sector')) {
                $query->where('sector', $request->sectors_sector);
            }

            // Filtra registros onde type NÃO CONTÉM "POP"
            $query->where('type', 'LIKE', '%POP%');

            $Files = $query->get();
        }

        return view('pop_clients_files.index', compact('Clients', 'Systems', 'Files'));
    }

    public function destroy($id, Request $request)
    {
        $filePath = storage_path('app/' . $request->path . $request->file);

        if (file_exists($filePath)) {
            unlink($filePath);
            Files::findOrFail($id)->delete();
            return redirect('pop_clients_files')->with('success', 'Arquivo Deletado com Sucesso');
        }

        return redirect('pop_clients_files')->with('success', 'Arquivo Deletado com Sucesso');
    }

    public function download($file)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar logado para acessar esta página.');
        }

        // Buscar no banco de dados o caminho do arquivo
        $fileEntry = Files::where('file', $file)->first();

        if (!$fileEntry) {
            return redirect()->route('pop_clients_files')->with('error', 'Arquivo não encontrado no banco de dados.');
        }

        // Montar o caminho completo baseado no registro do banco
        $filePath = storage_path("app/" . $fileEntry->path . $fileEntry->file);

        if (!file_exists($filePath)) {
            return redirect()->route('pop_clients_files')->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        return response()->download($filePath);
    }

    public function showPDF($id)
    {
        $file = Files::findOrFail($id);
        $filePath = storage_path("app/" . $file->path . $file->file);

        if (!file_exists($filePath)) {
            return redirect()->route('pop_clients_files.index')->with('error', 'O arquivo não existe no sistema de arquivos.');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $file->file . '"'
        ]);
    }
}