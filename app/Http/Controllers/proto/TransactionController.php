<?php

namespace App\Http\Controllers\proto;

use App\ClosedCash;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Payment;
use App\Product;
use App\Transaction;
use App\TransactionDetail;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    private $tx;
    private $detail_tx;
    private $product;
    private $temp_tx;

    function __construct() {}

    function init() {
        $this->tx = Transaction::instance(auth()->user());
        $this->detail_tx = TransactionDetail::instance();
        $this->product = Product::instance();
    }

    function index(Request $req, $id = null){
        $this->init();

        $start_date = $req->start_date;
        $end_date = $req->end_date;
        $all = $req->all;
        if ($all == 'on' || $all) $completed = true;
        else $completed = false;

        if ($start_date == null || $end_date == null){
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        }

        $data['list'] = Transaction::find_all($start_date, $end_date, $completed);
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        $summary_start_date = Transaction::get_last_closed_cash();
        $data['summaries'] = Transaction::query_tx_summary($summary_start_date);
        $data['total_payment'] = Payment::find_total_payment()->total_payment;
        $count_tx = $this->tx->select(DB::raw("ifnull(count(id), 0) as count"))->where('status', '=', 1)->whereNull('close_cash_id')->orWhere('close_cash_id', '=', "")->get();
        if (count($count_tx) > 0) $data['tx_count'] = $count_tx[0]->count;
        return view('admin.transaction.list', $data);
    }

    function transaction(Request $req, $id = null){
        $this->init();
        $company = auth()->user()->company()->first();

        $tx_pending_query = Transaction::instance()
            ->select(
                DB::raw("(SELECT tb_invoice.nomor FROM tb_invoice WHERE tb_invoice.id = tb_transaction.invoice_id LIMIT 1) AS 'nomor'"),
                'tb_transaction.*',
                DB::raw("( SELECT SUM((price * qty) - ((price * qty) * discount_pct /100)) AS total FROM tb_transaction_detail WHERE transaction_id = tb_transaction.id ) AS subtotal")
            )
//            ->groupBy('tb_transaction.id')
            ->where('status', '=', '0')
            ->where('close_cash_id', '=', null)
            ->limit(1);
        $tx_pending = $tx_pending_query->get();

        if ($id == null && count($tx_pending) > 0) {
            $id = $tx_pending[0]->id;
        }

        $search = $req->input('_search');
        $data['search'] = $search;

        $data['company_name'] = $company->name;

        $data['tx_pending'] = $tx_pending;

        if ($search == null || $search == '') {
            $data['product'] = []; //$this->product->select('id', 'code', 'name', 'price')->get();
        } else {
            $data['product'] = $this->product->select('id', 'code', 'name', 'price')
                ->where('code', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%')
                ->orWhereHas('category', function($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                })->get();

            // if (count($data['product']) == 0) {
            //     return redirect('transaction/form/'.$id)->with(['tx_id' => $id]);
            // }
        }
        $data['id'] = $id;

        if ($id == null)
            $data['tx_detail'] = array();
        else {
            $data['tx_detail'] = $this->detail_tx->select(
                'tb_transaction_detail.*',
                DB::raw("(SELECT name FROM tb_product WHERE tb_transaction_detail.product_id = id LIMIT 1) AS 'name'")
            )
                ->where('transaction_id', '=', $id)
                ->orderBy('tb_transaction_detail.created_at', 'ASC')
                ->get();

            $data['tx_head'] = $tx_pending_query->where('tb_transaction.id', '=', $id)->first();
        }
        return view('admin.transaction.form', $data);
    }

    function save(Request $req, $id = null){
        $company_id = auth()->user()->company_id;
        $tx_id = Transaction::create([
            'invoice_id' => $id,
            'remark' => '',
            'tx_date' => date('Y-m-d')
        ]);
        $tx_detail_id = Transaction::create_detail([
            'transaction_id' => $tx_id,
            'product_id' => $req->product,
            'price' => $req->price,
            'qty' => $req->qty,
            'discount_pct' => ($req->discount == null)?0:$req->discount,
            'tax_pct' => 0,
            'service_pct' => 0,
            'company_id' => $company_id
        ]);
        // return ['company_id' => $company_id];
       // return ['tx_id' => $tx_id, 'tx_detail_id' => $tx_detail_id];
        return redirect('transaction/form/'.$tx_id)->with(['tx_id' => $tx_id, 'tx_detail_id' => $tx_detail_id]);
    }

    function payment(Request $req, $id = null){
        if ($id == null) return redirect(route('trx.form'));
        $this->init();
        $payment = [
            'id' => Str::uuid()->toString(),
            'invoice_id' => $id,
            'date' => date('Y-m-d'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => auth()->user()->username,
            'company_id' => auth()->user()->company_id
        ];

        $tx_query = Invoice::instance()
            ->select(
                DB::raw("SUM(
		(SELECT
				SUM((price * qty) - ((price * qty) * discount_pct /100)) AS total
                FROM tb_transaction
                	INNER JOIN tb_transaction_detail ON transaction_id = tb_transaction.id
                WHERE
                	tb_transaction.invoice_id = tb_invoice.id
                GROUP BY tb_transaction.id)
           ) AS total"),
                DB::raw("(SELECT SUM(total_paid) FROM tb_payment WHERE invoice_id = tb_invoice.id) AS total_paid")
            )
            ->groupBy('tb_invoice.id')
            ->where('tb_invoice.company_id', '=', auth()->user()->company_id)
            ->where('tb_invoice.id', '=', $id);
        $tx_total = $tx_query->first();
        if ($tx_total != null && $tx_total->total > $tx_total->total_paid) {
            $paid = $req->payment + $tx_total->total_paid;
            if ($paid >= $tx_total->total) {
                $this->tx->where('id', '=', $id)->update(['status' => '1']);
                $payment['total_paid'] = $tx_total->total - $tx_total->total_paid;
            }else
                $payment['total_paid'] = $req->payment;

            $result = Payment::create($payment);
        }
        return redirect(route('trx.form'));
    }

    function plus(string $tx_id, string $tx_detail_id){
        $this->init();
        $query = $this->detail_tx->where('transaction_id', '=', $tx_id)
            ->where('id', '=', $tx_detail_id);
        $tx_detail = $query->first();
        if ($tx_detail != null){
            $query->update(['qty' => ($tx_detail->qty+1)]);
        }
        return redirect(route('trx.form.edit', $tx_id));
    }

    function minus(string $tx_id, string $tx_detail_id){
        $this->init();
        $query = $this->detail_tx->where('transaction_id', '=', $tx_id)
            ->where('id', '=', $tx_detail_id);
        $tx_detail = $query->first();
        if ($tx_detail != null){
            $qty = ($tx_detail->qty-1);
            if ($qty > 0) $query->update(['qty' => $qty]);
            else $query->delete();
        }
        return redirect(route('trx.form.edit', $tx_id));
    }

    function process_close_cash(){
        $this->init();
        DB::transaction(function(){
            $close_id = Str::uuid()->toString();
            $company_id = auth()->user()->company_id;
            $query_setting = DB::table('tb_company_setting')->where("company_id", '=', $company_id)
                ->where('key', '=', 'last_close_cash_number');
            $nomor = $query_setting->select('value')->first();
            if ($nomor == null){
                DB::table('tb_company_setting')->insert([
                    'company_id' => $company_id,
                    'key' => 'last_close_cash_number',
                    'value' => 0
                ]);
                $nomor = 1;
            } else {
                $nomor = $nomor->value + 1;
            }

            $summary_start_date = Transaction::get_last_closed_cash();
            $data = Transaction::query_tx_summary($summary_start_date);
            $total = Transaction::get_total_tx_summary($summary_start_date);
            $payment = Payment::find_total_payment()->total_payment;

            $first = ($this->tx->whereNull('close_cash_id')->orWHere('close_cash_id', '=', ''))
                ->join('tb_invoice', 'invoice_id', '=', 'tb_invoice.id')
                ->where('tb_invoice.company_id', '=', $company_id)
                ->where('status', '=', 1)
                ->orderBy('tx_date', 'ASC')
                ->orderBy('nomor', 'ASC')
                ->first();
            $close = [
                'id' => $close_id,
                'company_id' => $company_id,
                'nomor' => $nomor,
                'start_time' => date('Y-m-d H:i:s', strtotime($first != null?$first->created_at:date('Y-m-d H:i:s'))),
                'close_time' => date('Y-m-d H:i:s'),
                'closed_by' => auth()->user()->username,
                'data' => json_encode($data),
                'total' => $total,
                'total_paid' => $payment
            ];
    //        return $close;

            $result = ClosedCash::create($close);
            if ($result){
                $this->tx->where('tb_transaction.company_id', '=', $company_id)
                    ->whereNull('close_cash_id')->orWhere('close_cash_id', '=', '')
                    ->update(['close_cash_id' => $close_id]);
                Payment::instance()
                    ->whereNull('close_cash_id')->orWhere('close_cash_id', '=', '')
                    ->update(['close_cash_id' => $close_id]);
                $query_setting->update(['value' => $nomor]);
            }
        });
        return redirect(route('trx.list'));
    }

    function detail($id = null){
        $this->init();
        if ($id == null) return redirect(route('trx.list'));
        $data['header'] = $this->tx->where('id', '=', $id)->first();
        $data['detail'] = $data['header']->get_details();
        $data['invoice'] = $data['header']->get_invoice();
        $data['payments'] = $data['invoice']->get_payments();
        return view('admin.transaction.detail', $data);
    }


}
