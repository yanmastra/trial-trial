<?php

namespace App\Http\Controllers\proto;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $users;

    public function init(){
        $this->users = auth()->user();
    }

    function index($id = null){
        if ($id != null){
            $data['user'] = auth()->user()->where('id', '=', $id)->first();
        }
        $data['list'] = Auth::user()->get_users();
        return view('myadmin.user_list', $data);
    }

    function save(Request $req, $id = null){
        if ($req->password != $req->confirm_password) return redirect('user'.@'/'.$id)->with(['message' => "Invalid confirm password!"]);

        $company_id = \auth()->user()->company_id;
        $data = [
            'company_id' => $company_id,
            'username' => $req->username,
            'email' => $req->email,
            'name' => $req->name,
            'type' => 'COMPANY',
        ];
        if ($req->password != ''){
            $data['password'] = $req->password;
        }
        if ($id == null){
            $already = User::where('username', '=', $req->username)->first() != null;
            if ($already) return redirect('user')->with(['message' => "Username already exists!"]);

            $data['id'] = Str::uuid()->toString();
            $result = User::create($data);
            if ($result) return redirect('user')->with(['message' => "User created"]);
            else return redirect('user')->with(['message' => "Failed to save user data!"]);
        }else{
            $data['id'] = $id;
            $result = User::where('id', '=', $id)->update($data);
            if ($result) return redirect('user')->with(['message' => "User updated"]);
            else return redirect('user'.@'/edit/'.$id)->with(['message' => "Failed to update user data!"]);
        }
    }

    function delete($id = null){
        if ($id == null) return redirect('user');
        \auth()->user()->where('id', '=', $id)->delete();
        return redirect('user');
    }
}
