<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payment extends Model
{
    //
    public $table = "tb_payment";
    public $timestamps=true;
    public $incrementing=false;
    protected $primaryKey='id';
    protected $granted = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['id' ,'total_paid', 'invoice_id','company_id', 'date', 'created_at', 'created_by', 'deleted_at'];

    public static function instance(){
        $payment = new Payment();
        $payment = $payment->where('tb_payment.company_id', '=', auth()->user()->company_id);
        return $payment;
    }

    static function find_total_payment(){
        return DB::table('tb_payment')->select(DB::raw("ifnull(sum(total_paid), 0) AS total_payment"))
            ->where('company_id', '=', auth()->user()['company_id'])
            ->whereNull('close_cash_id')
            ->orWhere('close_cash_id', '=', '')
            ->first();
    }
}
