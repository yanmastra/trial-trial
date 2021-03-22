<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller{
    use AuthenticatesUsers;

    protected $redirectTo = "/dashboard";

    public function __construct(){
        $this->middleware('guest')->except('logout');
        Session::flush();
    }

    public function login(Request $request){
        $this->validate(
            $request,
            [
                $this->username() => 'required|string',
                'password' => 'string',
            ],
            [
                'username.required' => 'Username is required',
                'password.string' => 'Incompatible type',
            ]
        );
        if (\Auth::attempt(['username' => $request->username, 'password' => $request->password])) {

            $user = auth()->user();
            if ($user->type == 'SYSTEM') {
                return redirect('myadmin/company');
            }else{
                $user->config();
                return redirect('/dashboard');
            }
        }else{
            return redirect('/login')->with('error', 'Invalid username or password!');
        }
    }

    function index(){
        return view('auth');
    }

    function username(){ return "username";}

    protected function validateLogin(Request $request){
        // $this->validate(
        //     $request,
        //     [
        //         $this->username() => 'required|string',
        //         'password' => 'string',
        //     ],
        //     [
        //         'username.required' => 'Username is required',
        //         'password.string' => 'Incompatible type',
        //     ]
        // );
    }
}
