<?php

namespace App\Http\Controllers;


use App\Models\User;

use App\Models\Users;
use App\Models\Clients;
use App\Models\Systems;
use App\Models\Files;

use Illuminate\Http\Request;

use stdClass;

class pop_oper_bkpController extends Controller
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
    public function index()
    {
        //$this->authorize('is_admin');
        $Clients = Clients::all();
        $Users = Users::all();
        $Systems = Systems::all();

        return view('uploads.pop_oper_bkp.index', ['Clients' => $Clients], ['Systems' => $Systems], ['Users' => $Users]);
    }

    public function store(Request $request)
{
    // Verifica se há arquivos no request e se é um array
    if ($request->hasFile('upload') && is_array($request->file('upload'))) {
        foreach ($request->file('upload') as $requestUpload) {
            if ($requestUpload->isValid()) {
                $file = new Files;

                // Criando o caminho do diretório
                $file->users_name = $request->users_name;
                $file->clients_client = $request->clients_client;
                $file->systems_system = $request->systems_system;
                $file->type = $request->type;
                $file->sector = "OPERACAO";
                $file->path = 'storage/received_file/' . $request->clients_client . '/' . $request->systems_system . '/' . $request->type .'/';
                
                // Criando nome do arquivo
                $uploadName = pathinfo($requestUpload->getClientOriginalName(), PATHINFO_FILENAME).'.'.$requestUpload->getClientOriginalExtension();

                // Movendo o arquivo para a pasta desejada
                $requestUpload->move(public_path($file->path), $uploadName);

                // Salvando no banco de dados
                $file->file = $uploadName;
                $file->save();
            }
        }
    }

    return redirect('pop_oper_bkp')->with('success', 'Upload realizados com sucesso');
}

}