<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    public $table = "tb_company_setting";
    public $timestamps=false;
    public $incrementing=true;
    protected $primaryKey='id';
    protected $hidden = [];
    protected $fillable = ['id', 'company_id', 'key', 'value'];

    public function company(){
    	return $this->belongsTo('App\Company', 'company_id', 'id');
    }
}
