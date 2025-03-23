<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Systems;

class SystemsController extends Controller
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
        $this->authorize('is_admin');
        $Systems = Systems::all();
        return view('systems.index', compact('Systems'));
    }

    public function store(Request $request)
    {

        $systems = new Systems;

        $systems->name = $request->system;

        $systems->save();

        return redirect('systems')->with('success', 'Sistema Cadastrado com Sucesso');
    }

    public function edit($id)
    {

        $Systems = Systems::findOrFail($id);

        return view('update.edit_system.index', ['Systems' => $Systems]);

    }

    public function update(Request $request)
    {

        $data = $request->all();

        Systems::findOrFail($request->id)->update($data);

        return redirect('systems')->with('success', 'Sistema Atualizado com Sucesso');

    }

    public function destroy($id)
    {

        Systems::findOrFail($id)->delete();

        return redirect('systems')->with('success', 'Sistema Deletado com Sucesso');

    }

}