<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Sectors;
use App\Models\Profiles;
use App\Models\Users;

class UsersController extends Controller
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
        $Users = Users::all();
        $Sectors = Sectors::all();
        $Profiles = Profiles::all();
        $Users = Users::with(['sector','profile'])->get();
        return view('users.index', compact('Users','Sectors' , 'Profiles'));
    }
    public function store(Request $request)
    {

        try {
            
            $sector = Sectors::where('name', $request->sectors_sector)->first();
            $profile = Profiles::where('name', $request->profiles_profile)->first();

            $users = new Users;
            $users->name = $request->name;
            $users->email = $request->email;
            $users->password = Hash::make($request->password);
            $users->sector_id = $sector->id; // Associando o setor
            $users->profile_id = $profile->id;// Associando o perfil
            $users->admin_lte_dark_mode = $request->admin_lte_dark_mode;

            $users->save();

        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() === '23000') {
                return redirect('users')->with('error', 'Email ja  Cadastrado');
            }

        }

        return redirect('users')->with('success', 'Usuário Cadastrado com Sucesso');

    }

    public function edit($id) {

        $Users = Users::findOrFail($id);
        $Sectors = Sectors::all();

        return view('update.edit_user.index', compact('Users','Sectors'));

    }
    public function update(Request $request)
    {

        $data = $request->all();

        $data['password']= Hash::make($request->password);

        Users::findOrFail($request->id)->update($data);

        return redirect('users')->with('success', 'Usuário Atualizado com Sucesso');

    }
    public function destroy($id)
    {

        Users::findOrFail($id)->delete();

        return redirect('users')->with('success', 'Usuário Deletado com Sucesso');

    }

}