<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

    public function __construct(Users $users)
    {
        $this->middleware('auth');
        $this->user = $users;
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
        $Users = Users::with(['sector', 'profile'])->get();
        return view('users.index', compact('Users', 'Sectors', 'Profiles'));
    }
    public function store(Request $request)
    {
        $existingUser = Users::where('email', $request->email)->first();

        if ($existingUser) {
            return redirect('users')->with('error', 'Email já cadastrado');
        }

        try {
            $sector = Sectors::where('name', $request->sectors_sector)->first();
            $profile = Profiles::where('name', $request->profiles_profile)->first();

            $users = new Users;
            $users->name = $request->name;
            $users->email = $request->email;
            $users->password = Hash::make($request->password);
            $users->sector_xid = $sector->xid;
            $users->profile_xid = $profile->xid;
            $users->admin_lte_dark_mode = '0';

            $users->save();

        } catch (\Exception $e) {
            return redirect('users')->with('error', 'Erro ao cadastrar usuário: ' . $e->getMessage());
        }

        return redirect('users')->with('success', 'Usuário cadastrado com sucesso');
    }

    public function edit($id)
    {

        $Users = Users::findOrFail($id);
        $Sectors = Sectors::all();
        $Profiles = Profiles::all();

        return view('update.edit_user.index', compact('Users', 'Sectors', 'Profiles'));

    }
    public function update(Request $request)
    {
        $data = $request->all();

        // Se uma senha for fornecida, a atualiza
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            // Caso contrário, mantemos a senha atual
            unset($data['password']);
        }

        Users::findOrFail($request->id)->update($data);

        return redirect('users')->with('success', 'Usuário Atualizado com Sucesso');
    }
    public function destroy($id)
    {

        Users::findOrFail($id)->delete();

        return redirect('users')->with('success', 'Usuário Deletado com Sucesso');

    }

}