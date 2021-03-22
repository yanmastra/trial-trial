<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $table = "tb_invoice";
    public $timestamps=true;
    public $incrementing=false;
    protected $primaryKey='id';
    protected $granted = ['updated_at', 'deleted_at'];
    protected $fillable = ['id' ,'nomor', 'company_id', 'created_at'];

    public static function instance()
    {
        $invoice = new Invoice();
        $invoice = $invoice->where('tb_invoice.company_id', '=', auth()->user()->company_id);
        return $invoice;
    }

    public function transactions(){
        return $this->toMany('App\Transaction', 'invoice_id', 'id');
    }

    public function payments(){
        return $this->hasMany('App\Payment', 'invoice_id', 'id');
    }

    public function get_payments(){
        $this->payments()->get();
    }
}
