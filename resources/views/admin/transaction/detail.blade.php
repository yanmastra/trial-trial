@extends('admin')
@section('title', 'Transaction')
@section('content')
    <br>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 offset-sm-3">
                    <div class="card card-default">
                        <div class="card-header">
                            <table border="0">
                                <tr>
                                    <td>Invoice</td>
                                    <td>:</td>
                                    <td>#{{@$invoice->nomor}}</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>:</td>
                                    <td>{{@date('d M Y', strtotime($header->tx_date))}}</td>
                                </tr>
                                <tr>
                                    <td>Remark</td>
                                    <td>:</td>
                                    <td>{{@$header->remark}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <th>Name</th>
                                    <th class="text-right">QTY</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Subtotal</th>
                                </thead>
                                <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @if(isset($detail) && count($detail) > 0)
                                    @foreach($detail as $item)
                                        @php
                                        $subtotal = ($item->price * $item->qty) - ($item->price * $item->qty * $item->discount_pct / 100);
                                        $total += $subtotal;
                                        @endphp
                                        <tr>
                                            <td>{{$item->get_product()->name}}</td>
                                            <td align="right">{{$item->qty}}</td>
                                            <td align="right">{{number_format($item->price, 2, ',', '.')}}</td>
                                            <td align="right">{{number_format($item->discount_pct, 2, ',', '.')}}</td>
                                            <td align="right">{{number_format($subtotal, 2, ',', '.')}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4">TOTAL</td>
                                        <td align="right">{{number_format($total, 2, ',', '.')}}</td>
                                    </tr>
                                @else
                                    <tr><td colspan="5">No item</td> </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
