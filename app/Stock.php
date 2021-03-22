<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //
    public $table = "tb_stock";
    public $timestamps=true;
    public $incrementing=false;
    protected $primaryKey='id';
    protected $granted = ['created_at', 'updated_at', 'deleted_at'];
    public $fillable = ['id', 'company_id', 'product_id', 'input_date', 'new_stock', 'stock_in_system', 'real_stock', 'remark', 'created_at'];

    public function product(){
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }

    public static function instance(){
        $stock = new Stock();
        $stock = $stock->where('tb_stock.company_id', '=', auth()->user()['company_id']);
        return $stock;
    }
}
