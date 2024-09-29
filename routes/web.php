<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('');
// });

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('logout', function (Request $request){
    if (Auth::check()) Auth::logout();
    $request->session()->invalidate();
    return redirect('/');
});

Route::group(['prefix' => 'img'], function(){
    Route::get('/company/{id}', 'myadmin\CompanyController@img');
});

Route::middleware('guest')->group(function(){
    Route::get("/", 'auth\LoginController@index')->name('login');
    Route::get('myadmin/login', 'auth\LoginController@index');
    Route::get("/login", 'auth\LoginController@index')->name('login2');
    Route::post("/login", 'auth\LoginController@login')->name('login');
    Route::post('myadmin/login', 'auth\LoginController@login');
});


Route::middleware(['auth:web', 'App\Http\Middleware\AdminCompany'])->group(function(){

	Route::get('/', 'proto\DashboardController@index');
	Route::group(['prefix' => 'dashboard'], function(){
		Route::get('/', 'proto\DashboardController@index');
		Route::get('/{any}', function(){ return redirect('dashboard');});
	});

	Route::group(['prefix' => 'product'], function(){
		Route::get('/', 'proto\ProductController@index')->name('product.list');
		Route::get('/create', 'proto\ProductController@form');
        Route::get('/delete/{id}', 'proto\ProductController@delete');
		Route::get('/edit/{id}', 'proto\ProductController@index');
        Route::get('/stock/{id}', 'proto\ProductController@stock');
        Route::get('/print', 'proto\ProductController@print');
        Route::get('/check_stock/{id}', 'proto\ProductController@check_stock');
        Route::get('/{id}', 'proto\ProductController@detail')->name('product.detail');
        Route::post('/up_stock/{id}', 'proto\ProductController@up_stock');
        Route::post('/check_stock/{id}', 'proto\ProductController@save_diff');
		Route::post('/save', 'proto\ProductController@save');
		Route::post('/save/{id}', 'proto\ProductController@save');
	});

	Route::group(['prefix' => 'category'], function(){
		Route::get('/', 'proto\CategoryController@index');
		Route::get('/create', 'proto\CategoryController@form');
		Route::get('/edit/{id}', 'proto\CategoryController@edit');
        Route::get('/delete/{id}', 'proto\CategoryController@delete');
        Route::post('/save', 'proto\CategoryController@save');
        Route::post('/save/{id}', 'proto\CategoryController@save');
	});

	Route::group(['prefix' => 'log_stock'], function() {
	    Route::get('/', 'proto\StockController@index')->name('stock_log.list');

    });

    Route::group(['prefix' => 'close_cash'], function() {
        Route::get('/', 'proto\CloseCashController@index')->name('close_cash.list');

    });
    Route::group(['prefix' => 'user'], function() {
        Route::get('/', 'proto\UserController@index')->name('user.list');
        Route::get('/edit/{id}', 'proto\UserController@index')->name('user.list');
        Route::get('/delete/{id}', 'proto\UserController@delete');
        Route::post('/save', 'proto\UserController@save')->name('user.save');
        Route::post('/save/{id}', 'proto\UserController@save');

    });

	Route::group(['prefix' => 'transaction'], function (){
	    Route::get('/', 'proto\TransactionController@index')->name('trx.list');
        Route::get('/form', 'proto\TransactionController@transaction')->name('trx.form');
        Route::get('/form/{id}', 'proto\TransactionController@transaction')->name('trx.form.edit');
        Route::get('/plus/{tx_id}/{tx_detail_id}', 'proto\TransactionController@plus')->name('trx.plus');
        Route::get('/minus/{tx_id}/{tx_detail_id}', 'proto\TransactionController@minus')->name('trx.minus');
        Route::get('/process_close_cash', 'proto\TransactionController@process_close_cash')->name('trx.closing_cash');
        Route::get('/detail/{id}', 'proto\TransactionController@detail')->name('trx.detail');
        Route::post('/save', 'proto\TransactionController@save')->name('trx.save');
        Route::post('/save/{id}', 'proto\TransactionController@save')->name('trx.save2');
        Route::post('/payment/{id}', 'proto\TransactionController@payment')->name('trx.payment');
    });
});

Route::middleware(['auth:web', 'App\Http\Middleware\RootSystem'])->group(function(){
	Route::get('/', 'myadmin\CompanyController@index')->name('company-list');

	Route::group(['prefix' => 'myadmin'], function(){
	    Route::get('/', 'myadmin\CompanyController@index');

		Route::group(['prefix' => 'company'], function(){
			Route::get('/', 'myadmin\CompanyController@index');
            Route::get('/detail/{id}', 'myadmin\CompanyController@detail')->name('company.detail');
            Route::get('/detail/{id}/user/{user_id}', 'myadmin\CompanyController@detail');
			Route::get('/edit/{id}', 'myadmin\CompanyController@index')->name('company.edit');
            Route::post('/save_user/{company_id}', 'myadmin\CompanyController@save_company_user');
            Route::post('/save_user/{company_id}/{id}', 'myadmin\CompanyController@save_company_user');
			Route::post('/save', 'myadmin\CompanyController@save');
			Route::post('/save/{id}', 'myadmin\CompanyController@save');
		});

		Route::group(['prefix' => 'user'], function(){
		    Route::get('/', 'myadmin\AdminRootController@index')->name('admin.user.list');
            Route::get('/edit/{id}', 'myadmin\AdminRootController@index')->name('admin.user.list');
            Route::post('/save', 'myadmin\AdminRootController@save')->name('admin.user.save');
            Route::post('/save/{id}', 'myadmin\AdminRootController@save')->name('admin.user.save');
        });
	});

});


