@extends('admin')
@section('title', 'Transaction')
@section('content')
    <br>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h2>Transactions</h2>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{url('transaction/form')}}" class="btn btn-primary btn-sm" >
                                        <i class="fa fa-cart-plus"></i>
                                        New Transaction
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{url('transaction')}}" method="get">
                                <div class="row">
                                    <div class="col-sm-1 text-right">
                                        <label>Filter from</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" name="start_date" type="date" placeholder="Start date" value="{{isset($_GET['start_date'])?$_GET['start_date']:date('Y-m-d')}}">
                                        <p>
                                            <input type="checkbox" name="all" <?=isset($_GET['all'])?'checked':'' ?> />
                                            Include closed transactions ?
                                        </p>
                                    </div>
                                    <div class="col-sm-1 text-right">
                                        <label>to</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" name="end_date" type="date" placeholder="Start date" value="{{isset($_GET['end_date'])?$_GET['end_date']:date('Y-m-d')}}">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-default">Filter</button>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Date</th>
                                        <th>Remark</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($list) && count($list) > 0)
                                        @php
                                            $total = 0;
                                        @endphp
                                        @foreach($list as $item)
                                            <tr>
                                                <td align="center">{{$item->nomor}}</td>
                                                <td align="left">{{date('d M Y', strtotime($item->tx_date))}}</td>
                                                <td align="center">{{$item->remark}} {{$item->close_cash_id == null ? '' : 'Closed'}}</td>
                                                <td align="right">{{number_format($item->total, 2, ',', '.')}}</td>
                                                <td align="right">
                                                    <a class="btn btn-primary btn-sm" href="{{ url('transaction/detail/'.$item->id) }}">Detail</a>
                                                </td>
                                            </tr>
                                            @php
                                                $total += $item->total;
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td colspan="3" align="right">TOTAL</td>
                                            <td align="right">{{number_format($total, 2, ',', '.')}}</td>
                                            <td></td>
                                        </tr>
                                    @else
                                        <tr><td colspan="5" align="center">No Transaction</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card card-default">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h2>Sales Summaries</h2>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="#" onclick="actionConfirm({{'\''.url('transaction/process_close_cash').'\''}})" class="btn btn-primary btn-sm" >
                                        <i class="fa fa-cart-plus"></i>
                                        Close Cash
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="overflow: scroll;">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <th>Product</th>
                                <th>QTY</th>
                                <th>Total Discount</th>
                                <th>Total Cost</th>
                                <th>Total Gross</th>
                                <th>Total Neto</th>
                                </thead>
                                <tbody>
                                @if(isset($summaries) && count($summaries) > 0)
                                    @php
                                        $total_gross = 0;
                                        $total_cost = 0;
                                        $total_net = 0;
                                        $total_discount = 0;
                                    @endphp
                                    @foreach($summaries as $i => $item)
                                        <tr>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->qty}}</td>
                                            <td align="right">{{ number_format($item->total_discount, 2, ',', '.')}}</td>
                                            <td align="right">{{ number_format($item->total_cost, 2, ',', '.') }}</td>
                                            <td align="right">{{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                            <td align="right">{{ number_format(($item->subtotal - $item->total_cost), 2, ',', '.') }}</td>
                                        </tr>
                                        @php
                                            $total_gross += $item->subtotal;
                                            $total_cost += $item->total_cost;
                                            $total_net += ($item->subtotal - $item->total_cost);
                                            $total_discount += $item->total_discount;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="2" align="right">TOTAL</td>
                                        <td align="right">{{ number_format($total_discount, 2, ',', '.')}}</td>
                                        <td align="right">{{ number_format($total_cost, 2, ',', '.') }}</td>
                                        <td align="right">{{ number_format($total_gross, 2, ',', '.') }}</td>
                                        <td align="right">{{ number_format($total_net, 2, ',', '.') }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="6" align="center">No items</td>
                                    </tr>
                                @endif
                                    <tr><td colspan="6"></td></tr>
                                    <tr>
                                        <td colspan="5">Total Payment</td>
                                        <td align="right"><h4>{{@number_format($total_payment, 2, ',', '.')}}</h4></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">Transaction Count</td>
                                        <td align="right"><h4>{{@number_format($tx_count, 2, ',', '.')}}</h4></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function actionConfirm(url) {
            var a = confirm("Are you sure you want to close cash ?");
            if(a) window.location = url;
        }
    </script>
@endsection
