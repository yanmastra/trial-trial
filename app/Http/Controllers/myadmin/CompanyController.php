<?php

namespace App\Http\Controllers\myadmin;

use App\Http\Controllers\Controller;
use App\User;
use finfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Company;
use Auth;

class CompanyController extends Controller{

    public function index($id = null){
    	$comp = new Company();
    	$data['company'] = $comp->orderBy('created_at', 'DESC')->get();
    	if ($id != null) {
    	    $data['id'] = $id;
    		$data['edit'] = $comp->where('id', '=', $id)->first();
    	}
    	return view('myadmin/company_list', $data);
    }

    public function detail($id = null){
        if ($id == null) return redirect(route('company.list'));
        $comp = (new Company())->where('id', '=', $id)->first();
        $data['item'] = $comp;
        $data['users'] = $comp->get_users();
        $data['product'] = $comp->get_products();
        $data['company_id'] = $id;
//        return $data;
        return view('myadmin.company_detail', $data);
    }

    public function save_company_user(Request $r, $company_id = null, $id = null){
        if ($company_id == null) return redirect(route('company.list'));
        if ($r->password !== $r->confirm_password) return redirect(route('company.detail', $company_id))->with(['message' => "Confirm password incorrect!", 'type' => 'error']);

        $user = [
            'name' => $r->name,
            'email' => $r->email,
            'company_id' => $company_id
        ];

        if ($id != null){
            if ($r->password != null && $r->password != ''){
                $user['password'] = $r->password;
            }
            $res = User::where('id', '=', $id)->insert($user);
        }else {
            if ($r->password == null || $r->password == '') return redirect(route('company.detail', $company_id))->with(['message' => "Password required!", 'type' => 'error']);
            else {
                $user['password'] = $r->password;
                $user['username'] = $r->username;
                $user['id'] = Str::uuid()->toString();
                $user['created_at'] = date('Y-m-d H:i:s');
                $res = User::create($user);
            }
        }
        return redirect(route('company.detail', $company_id))->with(['message' => "User has been saved!", 'type' => 'success']);
    }

    public function save(Request $req, $id = null){
    	$comp = new Company();
    	$comp->name = $req->name;
    	$comp->phone = $req->phone;
    	$comp->email = $req->email;
    	$comp->address = $req->address;
    	$msg = "";
    	try{
            $file = $req->file('logo');
            if ($file != null) {
                $sFile = $file->openFile()->fread($file->getSize());
                $comp->logo = $sFile;
            }
        }catch (\Exception $e){
    	    $msg = $e->getMessage();
    	    $comp->logo = null;
        }
    	$comp->created_at = date('Y-m-d H:i:s');
    	$comp->created_by = auth()->user()->username;
    	if ($id == null) $id = Str::uuid()->toString();
    	$comp->id = $id;

    	try {
            $comp->save();
            return redirect('myadmin/company')->with('error', 'Company has been saved! '.$msg);
    	}catch (\Exception $e) {
    		try {
    		    $comp->where('id', '=', $id);
                $comp->update();
                return redirect('myadmin/company')->with('error', 'Company has been updated! '.$msg);
            }catch (\Exception $ex){
                return redirect('myadmin/company')->with('error', 'Unable to save or update this company! '.$msg);
            }
    	}
    }

    function img($id){
        $company = Company::select('logo')->where('id', '=', $id)->first();
        $result = true;
        if ($company->logo != null) {
            try {
                $content = response()->make($company->logo, 200, array(
                    'Content-Type' => (new finfo(FILEINFO_MIME))->buffer($company->logo)
                ));
            } catch (\Exception $e) {
                $result = false;
            }
        } else $result = false;

        if (!$result){
            $img = \DB::select("SELECT img FROM img WHERE name = 'no-image' LIMIT 1")[0];
            $content = response()->make($img->img, 200, array(
                'Content-Type' => (new finfo(FILEINFO_MIME))->buffer($img->img)
            ));
        }

        return $content;
    }

}
