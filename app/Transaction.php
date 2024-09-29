<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{

    public $table = "tb_transaction";
    public $timestamps=true;
    public $incrementing=false;
    protected $primaryKey='id';
    protected $granted = ['updated_at', 'deleted_at'];
    protected $fillable = ['id', 'tx_date', 'invoice_id', 'company_id', 'remark', 'status', 'created_at', 'created_by'];

    public function details(){
        return $this->hasMany('App\TransactionDetail', 'transaction_id', 'id');
    }

    public function get_details(){
        return $this->details()->get();
    }

    public function invoice(){
        return $this->belongsTo('App\Invoice', 'invoice_id', 'id');
    }

    public function get_invoice(){
        return $this->invoice()->first();
    }

    public static function instance(){
        $tx = new Transaction();
        $user = auth()->user();
        if ($user['type'] == 'COMPANY') $tx = $tx->where('tb_transaction.company_id', '=', $user->company_id);
        return $tx;
    }

    public static function find_all($start_date = null, $end_date = null, $complete = null){
        if ($start_date == null || $end_date == null){
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        }
        $query = DB::table("tb_transaction")->join('tb_invoice', 'invoice_id', '=', 'tb_invoice.id')
            ->select('tb_transaction.*', 'tb_invoice.nomor', DB::raw("
                (SELECT SUM((price * qty) - ((price * qty) * discount_pct /100)) FROM tb_transaction_detail WHERE transaction_id = tb_transaction.id) AS total
            "))
            ->whereBetween('tx_date', [$start_date, $end_date]);

        // echo $complete;
        if ($complete == 1 || $complete)
            $query = $query->where('status', '=', 1);

        $query = $query->orderBy('nomor');

        // dd($query);
        return $query->get();
    }

    /**
     * @param array $data [invoice_id, remark]
     * @return mixed|string|null
     */
    public static function create(array $data = []){
        if ($data == null || count($data) < 0) return null;
        $id = $data['invoice_id'];
        $company_id = auth()->user()->company_id;

        //validated invoice id
        if ($id == null || $id == '') $id = Str::uuid()->toString();
        $tx_id = $id;

        $result = null;
        $already = DB::table('tb_invoice')->where('id', '=', $id)->first();

        if ($already == null) {
            $last_number = DB::table('tb_company_setting')->select("value", "id")
                ->where('company_id', '=', $company_id)
                ->where('key', '=', 'last_invoice_number')
                ->first();
            $invoice = [
                'id' => $id,
                'nomor' => ($last_number->value + 1),
                'company_id' => $company_id,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $result = Invoice::create($invoice);
            if ($result != null) {
                $setting = DB::table('tb_company_setting')
                    ->where('id', '=', $last_number->id)
                    ->where('key', '=', 'last_invoice_number')
                    ->update(['value' => $invoice['nomor']]);
            }
        }

        if ($result != null){
            $already = DB::table('tb_transaction')->where('id', '=', $id)->first();
            $tx_data = [
                'id' => $tx_id,
                'invoice_id' => $id,
                'company_id' => $company_id,
                'tx_date' => $data['tx_date'],
                'remark' => $data['remark'],
                'status' => '0',
                'created_at' => date('Y-m-d'),
                'created_by' => auth()->user()->username,
            ];
            if ($already == null) {
                $result = DB::table('tb_transaction')->insert($tx_data);
            }
        }
        return $id;
    }

    public static function create_detail(array $data = null){
        if ($data == null) return null;

        $already = DB::table('tb_transaction_detail')
            ->where('transaction_id', '=', $data['transaction_id'])
            ->where('product_id', '=', $data['product_id'])
            ->where('price', '=', $data['price'])
            ->where('discount_pct', '=', $data['discount_pct'])
            ->first();

        if ($already != null){
            $data['id'] = $already->id;
            $data['qty'] = $data['qty'] + $already->qty;
            $result = TransactionDetail::where('id', '=', $data['id'])->update($data);
        }else {
            $data['id'] = Str::uuid()->toString();
            $result = TransactionDetail::create($data);
        }
        return $data['id'];
    }

    public static function get_last_closed_cash() {
        $company_id = auth()->user()->company_id;
        $start_date = DB::select("select close_time from closed_cash where company_id='$company_id' order by close_time DESC limit 1");
        if (count($start_date) > 0) {
            $start_date = $start_date[0]->close_time;
        } else {
            $start_date = date('Y-m-d');
        }
        return $start_date;
    }

    public static function query_tx_summary($start_date = null){
        $company_id = auth()->user()->company_id;
        if ($start_date == null) {
            $start_date = date('Y-m-d');
        }

        $sql = "SELECT 
TD.product_id as 'id',
(SELECT P.name FROM tb_product P WHERE P.id = TD.product_id) as 'name',
SUM(TD.qty) as 'qty',
COUNT(transaction_id) as 'transaction_count',
IFNULL((SUM(qty) * (SELECT cost_price FROM tb_product P WHERE P.id = TD.product_id)), 0) as 'total_cost',
IFNULL(SUM(qty * TD.discount_pct * TD.price / 100), 0) as 'total_discount',
IFNULL(SUM(qty * TD.price), 0) as 'subtotal'

FROM tb_transaction_detail TD LEFT JOIN tb_transaction T ON TD.transaction_id = T.id 
WHERE TD.qty > 0 AND (T.close_cash_id IS NUll OR T.close_cash_id = '') AND T.tx_date >= DATE_FORMAT('$start_date', '%Y-%m-%d') AND T.company_id='$company_id'
GROUP BY TD.product_id 
ORDER BY qty DESC
";

// echo $sql;

        $query = DB::select($sql);
        return $query;
    }

    public static function get_total_tx_summary($start_date = null){
        $company_id = auth()->user()->company_id;
        if ($start_date == null) {
            $start_date = date('Y-m-d');
        }

        return DB::select("SELECT IFNULL(SUM((D.price * qty) - (discount_pct * D.price / 100 * qty)), 0) AS total
        FROM tb_transaction T LEFT JOIN tb_transaction_detail D ON transaction_id = T.id
        WHERE (close_cash_id IS NULL OR close_cash_id = '') AND T.company_id = '$company_id' AND status = 1 
        AND T.tx_date >= DATE_FORMAT('$start_date', '%Y-%m-%d') AND T.company_id='$company_id'
        ")[0]->total;
    }

}
