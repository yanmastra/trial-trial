<?php

namespace App\Http\Controllers\myadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRootController extends Controller{

    function index($id = null){
        if ($id != null){
            $data['user'] = Auth::user()->users()->where('id', '=', $id)->first();
        }
        $data['list'] = Auth::user()->get_users();
        return view('myadmin.user_list', $data);
    }
}
