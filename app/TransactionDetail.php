<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    public $table = "tb_transaction_detail";
    public $timestamps=true;
    public $incrementing=false;
    protected $primaryKey='id';
    protected $granted = ['updated_at', 'deleted_at'];
    protected $fillable = ['id' ,'transaction_id','product_id','price', 'qty', 'discount_pct', 'tax_pct', 'service_pct', 'created_at', 'company_id'];

    public function product(){
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }

    public function get_product(){
        return $this->product()->first();
    }

    public static function instance()
    {
        $tx = new TransactionDetail();
        $user = auth()->user();
        if ($user['type'] == 'COMPANY') $tx = $tx->where('tb_transaction_detail.company_id', '=', $user->company_id);
        return $tx;
    }
}
