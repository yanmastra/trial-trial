@extends('admin')
@section('title', 'Stock Logs')
@section('content')
    <br>
    <style>
        .active{
            background-color: rgb(180, 180, 180);
            color: #000000;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Closed Cash list</h3>
                        </div>
                        <div class="card-body">
                            <div style="display: block; overflow: auto">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <th>No.</th>
                                <th style="min-width: 120px">Start Date</th>
                                <th style="min-width: 180px">Closed time</th>
                                <th>Close By</th>
                                <th>Total Sell</th>
                                <th>Total Payment</th>
                                <th style="min-width: 100px"></th>
                                </thead>
                                <tbody>
                                    @if(isset($list) && count($list) > 0)
                                        @foreach($list as $item)
                                        <tr id="row-{{$item->nomor}}">
                                            <td>{{$item->nomor}}</td>
                                            <td>{{date('d M Y H:i:s', strtotime($item->start_time))}}</td>
                                            <td>{{date('d M Y H:i:s', strtotime($item->close_time))}}</td>
                                            <td>{{$item->closed_by}}</td>
                                            <td align="right">{{number_format($item->total, 2, ',', '.')}}</td>
                                            <td align="right">{{number_format($item->total_paid, 2, ',', '.')}}</td>
                                            <td>
                                                <a class="btn btn-default btn-sm" id="btn-{{$item->nomor}}"
                                                   onclick="showDetail({{$item->nomor}})" href="#"
                                                   data-by="{{$item->closed_by}}"
                                                   data-start="{{date('d M Y H:i:s', strtotime($item->start_time))}}"
                                                   data-end="{{date('d M Y H:i:s', strtotime($item->close_time))}}"
                                                   data-detail='@php echo $item->data @endphp' >
                                                    Detail
                                                    <i class="fa fa-arrow-alt-circle-right"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7">No Item</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="modal-detail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Default Modal</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table border="0">
                        <tr>
                            <td>Start Date</td>
                            <td>:</td>
                            <td id="detail-start"></td>
                        </tr>
                        <tr>
                            <td>Close Time</td>
                            <td>:</td>
                            <td id="detail-end"></td>
                        </tr>
                        <tr>
                            <td>Close by</td>
                            <td>:</td>
                            <td id="detail-by"></td>
                        </tr>
                    </table>
                    <hr>
                    <div style="display: block; overflow: auto">
                    <table class="table table-bordered">
                        <thead>
                        <th>Product</th>
                        <th>QTY</th>
                        <th>Total Discount</th>
                        <th>Total Cost</th>
                        <th>Total Gross</th>
                        <th>Total Neto</th>
                        </thead>
                        <tbody id="detail-data">
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script>
        function showDetail(id) {
            var btn = $('#btn-'+id);
            var sData = btn.data('detail');
            console.log(sData);
            // var objData = JSON.parse(sData);
            var discount = 0, net = 0, cost = 0, total = 0;
            var content = "";
            for(var i = 0; i < sData.length; i++){
                var item = sData[i];
                content += "<tr>" +
                    "<td>" + item.name + "</td>" +
                    "<td align='right'>" + formatNumber(item.qty) + "</td>" +
                    "<td align='right'>" + formatNumber(item.total_discount) + "</td>" +
                    "<td align='right'>" + formatNumber(item.total_cost) + "</td>" +
                    "<td align='right'>" + formatNumber(item.subtotal) + "</td>" +
                    "<td align='right'>" + formatNumber(item.subtotal - item.total_cost) + "</td>" +
                    "</tr>";
                discount += item.total_discount;
                net += (item.subtotal - item.total_cost);
                cost += item.total_cost;
                total += item.subtotal;
            }
            content += "<tr>" +
                "<td colspan='2' align='right'>TOTAL:</td>" +
                "<td align='right'>"+formatNumber(discount)+"</td>" +
                "<td align='right'>"+formatNumber(cost)+"</td>" +
                "<td align='right'><b>"+formatNumber(total)+"</b></td>" +
                "<td align='right'><b>"+formatNumber(net)+"</b></td></tr>";


                $('#detail-data').html(content);
            $('#modal-title').html("Detail close cash nomor: <b>"+id+"</b>");
            $('#detail-start').html(btn.data('start'));
            $('#detail-end').html(btn.data('end'));
            $('#detail-by').html(btn.data('by'));
            $('#modal-detail').modal('show');
        }
    </script>
@endsection
