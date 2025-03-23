<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Types;

class TypesController extends Controller
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
        $Types = Types::all();
        return view('types.index', compact('Types') );
    }

    public function store(Request $request)
    {

        $types = new Types;

        $types->name = $request->type;

        $types->save();

        return redirect('types')->with('success', 'Tipo Cadastrado com Sucesso');
    }

    public function edit($id)
    {

        $Types = Types::findOrFail($id);

        return view('update.edit_type.index', ['Types' => $Types]);

    }

    public function update(Request $request)
    {

        $data = $request->all();

        Types::findOrFail($request->id)->update($data);

        return redirect('types')->with('success', 'Tipo Atualizado com Sucesso');

    }

    public function destroy($id)
    {

        Types::findOrFail($id)->delete();

        return redirect('types')->with('success', 'Tipo Deletado com Sucesso');

    }

}