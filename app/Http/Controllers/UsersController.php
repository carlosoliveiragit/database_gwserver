<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Users;
use stdClass;

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
        $return_db = Users::all();
        return view('users.index', ['Users' => $return_db]);
    }
    public function store(Request $request)
    {

        try {
            $users = new Users;

            $users->name = $request->name;
            $users->email = $request->email;
            $users->password = Hash::make($request->password);
            $users->profile = $request->profile;
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

        return view('update.edit_user.index', ['Users' => $Users]);

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