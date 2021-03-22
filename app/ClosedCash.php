<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClosedCash extends Model
{
    public $table = "closed_cash";
    public $timestamps=true;
    public $incrementing=false;
    protected $primaryKey='id';
    protected $granted = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['id', 'company_id', 'nomor', 'start_time', 'close_time', 'closed_by', 'data', 'total', 'total_paid'];

    public static function instance(){
        $cc = new ClosedCash();
        $cc = $cc->where('closed_cash.company_id', '=', auth()->user()['company_id']);
        return $cc;
    }

}
