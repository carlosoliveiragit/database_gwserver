<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Sectors;

class SectorsController extends Controller
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
        $return_db = Sectors::all();
        return view('sectors.index', ['Sectors' => $return_db]);
    }

    public function store(Request $request)
    {

        $Sectors = new Sectors;

        $Sectors->sector = $request->sector;

        $Sectors->save();

        return redirect('sectors')->with('success', 'Setor Cadastrado com Sucesso');
    }

    public function edit($id)
    {

        $Sectors = Sectors::findOrFail($id);

        return view('update.edit_sector.index', ['Sectors' => $Sectors]);

    }

    public function update(Request $request)
    {

        $data = $request->all();

        Sectors::findOrFail($request->id)->update($data);

        return redirect('sectors')->with('success', 'Setor Atualizado com Sucesso');

    }

    public function destroy($id)
    {

        Sectors::findOrFail($id)->delete();

        return redirect('sectors')->with('success', 'Setor Deletado com Sucesso');

    }

}