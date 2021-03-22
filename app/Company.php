<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    public $table = "tb_company";
    public $timestamps=true;
    public $incrementing=false;
    protected $primaryKey='id';
    protected $hidden = ['logo'];
    protected $fillable = ['id', 'name', 'phone', 'email', 'address', 'expired_date', 'status', 'created_at', 'created_by'];

    function users(){
    	return $this->hasMany('App\User', 'company_id', 'id');
    }

    public function get_users(){
        return $this->users()->get();
    }

    function product(){
        return $this->hasMany('App\Product', 'company_id', 'id');
    }

    function config(){
        return $this->hasMany('App\CompanySetting', 'company_id', 'id');
    }

    public function get_products(){
        return $this->product()->get();
    }

    public static function find($id){
        return (DB::table('tb_company')->where('id', '=', $id)->get()[0]);
    }

    function save(array $options = []){
        $res = parent::save($options);
        if ($res){
            $user = [
                'id' => Str::uuid()->toString(),
                'name' => 'Super Admin',
                'username' => 'admin',
                'password' => 'admin',
                'company_id' => $this->id,
                'email' => null,
            ];
            $res = User::create($user);
        }
        return $res;
    }

    function update(array $attributes = [], array $options = []){
        if (count($attributes) <= 0) {
            $company = [
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->address,
                'address' => $this->address,
                'logo' => $this->logo,
            ];
            $res = parent::update($company, $options);
        } else $res = parent::update($attributes, $options);
        return $res;
    }
}
