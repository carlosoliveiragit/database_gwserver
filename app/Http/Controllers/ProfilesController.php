<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Profiles;

class ProfilesController extends Controller
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
        $Profiles = Profiles::all();
        return view('profiles.index', compact('Profiles') );
    }

    public function store(Request $request)
    {

        $Profiles = new Profiles;

        $Profiles->name = $request->profile;

        $Profiles->save();

        return redirect('profiles')->with('success', 'Setor Cadastrado com Sucesso');
    }

    public function edit($id)
    {

        $Profiles = Profiles::findOrFail($id);

        return view('update.edit_profiles.index', compact('Profiles') );

    }

    public function update(Request $request)
    {

        $data = $request->all();

        Profiles::findOrFail($request->id)->update($data);

        return redirect('profiles')->with('success', 'Setor Atualizado com Sucesso');

    }

    public function destroy($id)
    {

        Profiles::findOrFail($id)->delete();

        return redirect('profiles')->with('success', 'Setor Deletado com Sucesso');

    }

}