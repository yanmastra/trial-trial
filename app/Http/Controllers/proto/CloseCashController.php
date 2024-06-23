<?php


namespace App\Http\Controllers\proto;


use App\ClosedCash;
use App\Http\Controllers\Controller;

class CloseCashController extends Controller
{
    private $cc;
    public function init()
    {
        $this->cc = ClosedCash::instance();
    }

    function index(){
        $this->init();
        $data['list'] = $this->cc->orderBy('nomor', 'DESC')->get();
        return view('admin.close_cash.list', $data);
    }
}
