<?php

namespace App\Http\Controllers\proto;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use \stdClass;

class ProductController extends Controller
{
    //

    private $product;
    private $category;
    public function __construct(){
    	$this->product = Product::instance();
    	$this->category = Category::instance();
    }

    function save(Request $req, $id = null){
        $product = [
            'id' => ($id == null)?Str::uuid()->toString():$id,
            'code' => ($req->code == '')?(new \DateTime())->getTimestamp():$req->code,
            'name' => $req->name,
            'company_id' => \auth()->user()->company_id,
            'category_id' => $req->category_id,
            'parent_id' => $req->parent_id,
            'unit_id' => $req->unit_id,
            'price' => $req->sell_price,
            'cost_price' => $req->cost_price,
        ];
        if ($id == null) {
            $product['stock'] = $req->stock;
            $result = Product::create($product);
        }
        else $result = Product::where('id', '=', $id)->update($product);
        return redirect(route('product.list'));
    }

    function detail($id){
        $product = $this->product->where('id', '=', $id)->first();
        return view('admin.product.detail', ['product' => $product]);
    }

    function delete($id){
        $this->product->where('id', '=', $id)->delete();
        return redirect(route('product.list'));
    }

    function index(Request $req, $id = null){
        $this->__construct();
    	$data['product'] = $this->product->get();
    	$data['category'] = Category::instance()->get();
        if ($id != null)
            $data['edit'] = $this->product->where('id', '=', $id)->first();
    	return view('admin.product.list', $data);
    }

    function stock($id = null){
        $this->__construct();
        if ($id == null) return redirect('product');
        $data['product'] = $this->product->where('id', '=', $id)->first();
        $data['id'] = $id;
        return view('admin.product.stock', $data);
    }

    function check_stock($id){
        $this->__construct();
        if ($id == null) return redirect('product');
        $data['product'] = $this->product->where('id', '=', $id)->first();
        $data['id'] = $id;
        $data['action'] = 'check';
        return view('admin.product.stock', $data);
    }

    function up_stock(Request $req, $id = null){
        $this->__construct();
        if ($id == null) return redirect('product');
        $p = $this->product->where('id', '=', $id)->first();
        $res = DB::table('tb_stock')->insert([
            'id' => Str::uuid()->toString(),
            'company_id' => \auth()->user()->company_id,
            'product_id' => $id,
            'input_date' => date('Y-m-d H:i:s'),
            'new_stock' => $req->stock,
            'stock_in_system' => $p->stock,
            'real_stock' => $p->stock,
            'remark' => 'Adding new product stock',
            'created_at' => date('Y-m-d H:i:s'),
            'type' => 'INPUT'
        ]);
        return redirect('product');
    }


    function save_diff(Request $request, $id){
        $this->__construct();
        $log_id = Str::uuid()->toString();
        $p = $this->product->where('id', '=', $id)->first();

        $data = [
            'id' => $log_id,
            'company_id' => \auth()->user()->company_id,
            'product_id' => $id,
            'input_date' => date('Y-m-d H:i:s'),
            'new_stock' => 0,
            'stock_in_system' => $p->stock,
            'real_stock' => @$request->stock,
            'remark' => @$request->remark,
            'created_at' => date('Y-m-d H:i:s'),
            'type' => 'CHECK'
        ];
        $result = DB::table('tb_stock')->insert($data);
        if ($result && $p->stock != $data['real_stock']){
            $this->product->where('id', '=', $id)->update(['stock' => $data['real_stock']]);
        }
        return redirect(route('product.list'));
    }
}
