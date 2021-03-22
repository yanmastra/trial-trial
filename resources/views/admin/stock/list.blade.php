@extends('admin')
@section('title', 'Stock Logs')
@section('content')
    <br>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Sales Summaries</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <th>Product</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Input Stock (pcs)</th>
                                <th>Stock In System (pcs)</th>
                                <th>Real Stock (pcs)</th>
                                <th>Diff (pcs)</th>
                                <th>Remark</th>
                                </thead>
                                <tbody>
                                @if(isset($list) && count($list) > 0)
                                    @foreach($list as $i => $item)
                                        <tr>
                                            <td>{{$item->product()->first()->name}}</td>
                                            <td align="left">{{$item->input_date}}</td>
                                            <td align="left">{{$item->type}}</td>
                                            <td align="right">{{ number_format($item->new_stock, 2, ',', '.') }}</td>
                                            <td align="right">{{ number_format($item->stock_in_system, 2, ',', '.') }}</td>
                                            <td align="right">{{ number_format($item->real_stock, 2, ',', '.') }}</td>
                                            <td align="right">{{ number_format(($item->stock_in_system - $item->real_stock), 2, ',', '.') }}</td>
                                            <td align="left">{{$item->remark}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" align="center">No items</td>
                                    </tr>
                                @endif
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
