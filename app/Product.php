<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    //
    public $table = "tb_product";
    public $timestamps=true;
    public $incrementing=false;
    protected $primaryKey='id';
    protected $granted = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['id' ,'code','name', 'stock', 'company_id', 'category_id', 'parent_id', 'unit_id', 'price' , 'cost_price' ];

    public function category(){
    	return $this->belongsTo('App\Category', 'category_id', 'id');
    }

    public function company(){
    	return $this->belongsTo('App\Company', 'company_id', 'id');
    }

    public static function instance(){
        $model = new Product();
        $user = auth()->user();
        if ($user['type'] == 'COMPANY')
            $model = $model->where('tb_product.company_id', '=', $user['company_id']);

        return $model;
    }
}
