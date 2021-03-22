<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    //
    public $table = "tb_category";
    public $timestamps=true;
    public $incrementing=false;
    protected $primaryKey='id';

    protected $fillable = ['id', 'name', 'parent_id', 'company_id'];

    public static function instance(){
        $model = new Category();
        $user = Auth::user();
        if ($user['type'] === 'COMPANY')
            $model = $model->where('tb_category.company_id', '=', $user['company_id']);

        return $model;
    }
}
