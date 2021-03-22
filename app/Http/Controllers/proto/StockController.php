<?php

namespace App\Http\Controllers\proto;

use App\Http\Controllers\Controller;
use App\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    private $stock;

    public function __construct(){
        $this->stock = Stock::instance();
    }

    public function index(){
        $this->__construct();
        $data['list'] = $this->stock->orderBy('input_date', 'DESC')->get();
        return view('admin.stock.list', $data);
    }
}
