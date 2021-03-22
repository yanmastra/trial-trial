<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public $table = "users";
    public $timestamps=true;
    public $incrementing=false;
    protected $primaryKey='id';

    protected $fillable = [
        'id', 'name', 'username', 'password', 'email', 'type', 'phone', 'address', 'company_id', 'created_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($password)
    {
        if ( !empty($password) ) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    public function company(){
        return $this->belongsTo('App\Company', 'company_id', 'id');
    }

    public function get_company(){
        return $this->company()->first();
    }

    public function users(){
        return $this->belongsTo('App\User', 'company_id', 'company_id');
    }

    public function config(){
        $config = null;
        if (\Session::has("config") && count(\Session::get('config')) > 0) {
            $config = \Session::get('config');
        } else {
            $cfg = $this->get_company()->config()->get();
            $config = [];
            foreach ($cfg as $value) {
                $config[$value->key] = $value->value;
            }
            \Session::put('config', $config);
        }
        return $config;
    }

    public function get_users(){
        return $this->users()->get();
    }

    public static function is_alredy($clause = array()){

    }

    public function find_all_users($company_id = null){
        $users = $this->orderBy('created_at', 'DESC');
        if ($company_id != null) $users->where('company_id', '=', $company_id);
        return $users->get();
    }
}
