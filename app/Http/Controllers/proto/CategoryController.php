<?php

namespace App\Http\Controllers\proto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Category;

class CategoryController extends Controller
{
    private $category;
    public function init()
    {
        $this->category = Category::instance();
    }

    function index(){
        $data['category'] = Category::instance()->get();
//        return $data;
    	return view('admin.category.list', $data);
    }

    function edit($id = null){
        $category = Category::instance();
        $data['category'] = $category->get();
        $edit = $category->where('id', '=', $id)->first();
        $data['id'] = $edit->id;
        $data['name'] = $edit->name;
        $data['parent_id'] = $edit->parent_id;
//        return $data;
        return view('admin.category.list', $data);
    }

    function delete($id = null){
        Category::instance()->where('id',  '=', $id)->delete();
        return redirect('/category');
    }

    function save(Request $req, $id = null){
    	$catg['name'] = $req->name;
    	$catg['company_id'] = auth()->user()['company_id'];
    	$catg['parent_id'] = $req->parent_id;
    	$category = Category::instance();

    	if ($id == null) {
    		$catg['id'] = Str::uuid()->toString();
            $category->create($catg);
    	}else{
    		$catg['id'] = $id;
    		if ($catg['id'] == $catg['parent_id']) $catg['parent_id'] = null;
            $category->where('id', '=', $id)->update($catg);
    	}
    	return redirect('/category');
    }
}
