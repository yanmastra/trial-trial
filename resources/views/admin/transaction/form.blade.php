@extends('admin')
@section('content')
    <style>
        .btn-focused:focus{
            border: solid 2px #128294;
            box-shadow: #c1d1df;
        }
    </style>
    <br>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <h4 class="card-title">New Sales</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{url('transaction/form/'.@$id)}}" method="get">
                                <!-- @csrf -->
                                <div class="row">
                                    <div class="col-lg-12">
                                            <label for='product_search'>Search product here</label>
                                        <div class="input-group">
                                            <input id='product_search' onkeyup="onTypingSearch()" value="{{ $search }}" name='_search' type="text" class="form-control form-control-lg" autocomplete="off"/>
                                            <div class="input-group-append">
                                                <button id="btn-search" type="submit" class="btn btn-info"> SEARCH </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <form action="{{url('transaction/save/'.@$id)}}" method="post" >
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="product_select">Product</label>
                                            <select id="product_select" onfocus="$(this).click()" name="product" class="form-control select2bs4" style="width: 100%;" onchange="applyPrice()" autofocus>
                                                {{$price = ''}}
                                                @if(isset($product) && count($product) > 0)
                                                    {{ $selected = ''}}
                                                    {{ $price = '' }}
                                                    @if(count($product) == 1)
                                                        {{ $selected = 'selected' }}
                                                        {{ $price = $product[0]->price * 100 / 100 }}
                                                    @elseif(count($product) > 1)
                                                        <option data-price="0" value="">Select Product</option>
                                                    @endif

                                                    @foreach($product as $item)
                                                        <option data-price="{{$item->price}}" value="{{$item->id}}" id="product_{{$item->id}}" {{$selected}}>{{$item->code." | ".$item->name." | ".number_format($item->price, 2, ',', '.')}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Price</label>
                                            <input type="number" id="product_price" name="price" value="{{ $price }}" class="form-control" placeholder="Product Price" 
                                            {{(\Session::has('config') && @(\Session::get('config')['change_price_while_tx']) == 1)?'':'readonly' }} 
                                            required/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>QTY</label>
                                            <input id="qty" type="number" value="1" min="0.00" onkeyup="applyQty()" class="form-control" name="qty" placeholder="Quantity" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Disc. Amount</label>
                                            <input id="discount_val" type="number" min="0.0" class="form-control" placeholder="Discount value" readonly disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Discount</label>
                                            <div class="input-group">
                                                <input id="discount" type="number" min="0.00" max="100.00" onkeyup="applyDiscount()"
                                                       class="form-control" name="discount" placeholder="Discount"
                                                       value="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Subtotal</label>
                                            <input id="subtotal" type="number" min="0.0" class="form-control" placeholder="Subtotal" readonly disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <br>
                                            <button type="submit" class="btn btn-primary btn-focused btn-lg">SAVE</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <form action="{{url('transaction/payment/'.@$id)}}" method="post">
                                @csrf
                                <table border="0" width="100%">
                                    <tr>
                                        <th width="100px">Total</th>
                                        <th> : </th>
                                        <th><h3 id="tx_total"></h3></th>
                                    </tr>
                                    <tr>
                                        <th>Payment</th>
                                        <th> : </th>
                                        <th>
                                            <input id="input_payment" class="form-control" name="payment" type="number" min="0.00" onfocus="applyTotal()" onkeyup="applyPayment()" required>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Change</th>
                                        <th> : </th>
                                        <th>
                                            <h3 id="tx_change"></h3>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            <button type="submit" class="btn btn-default btn-sm btn-focused">SAVE PAYMENT</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            <a href="{{ url('transaction/form') }}" class="btn btn-default btn-sm btn-focused">NEW TRANSACTION</a>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-default">
                                <div class="card-header">
                                    <h4 class="card-title">Saved Transaction</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <th>Invoice No.</th>
                                            <th>Transaction Date</th>
                                            <th>Total</th>
                                            <th></th>
                                        </thead>
                                        <tbody>
                                            @if(isset($tx_pending) && count($tx_pending) > 0)
                                                @foreach($tx_pending as $item)
                                                <tr>
                                                    <th>{{$item->nomor}}</th>
                                                    <th>{{ date('d M Y H:i:s', strtotime($item->tx_date))}}</th>
                                                    <th>{{ number_format($item->subtotal, 2, ',', '.')}}</th>
                                                    <th>
                                                        <a href="{{url('transaction/form/'.$item->id)}}" class="btn btn-default btn-sm">
                                                            Edit
                                                        </a>
                                                    </th>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="4" align="center">No Item</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="display: block; padding: 12px; border: solid 1px #dfdfdf; background-color: #fff">
                        <table border="0">
                            <tr>
                                <th colspan="4" align="center">{{@$company_name}}</th>
                            </tr>
                            <tr>
                                <th colspan="4"><br></th>
                            </tr>
                            <tr>
                                <th></th>
                                <td style="width: 100px">Invoice Number</td>
                                <th> : </th>
                                <th>{{@$tx_head->nomor}}</th>
                            </tr>
                            <tr>
                                <th></th>
                                <td>Transaction Date</td>
                                <th> : </th>
                                <th>{{(isset($tx_head->tx_date)?@date('d M Y H:i:s', strtotime($tx_head->tx_date)):date('d M Y H:i:s'))}}</th>
                            </tr>
                            <tr>
                                <th></th>
                                <td>Cashier</td>
                                <th> : </th>
                                <th>{{@$tx_head->created_by}}</th>
                            </tr>
                        </table>
                        <br>
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <th colspan="3" class="text-center">QTY</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                            @if(isset($tx_detail) && count($tx_detail) > 0 )
                                @php
                                    $total = 0;
                                    $discount = 0;
                                @endphp
                                @foreach(@$tx_detail as $item)
                                    @php
                                        $subtotal = $item->price * $item->qty;
                                        $disc = ($subtotal * $item->discount_pct / 100.0);
                                        $subtotal = $subtotal - $disc;
                                        $discount += $disc;
                                        $total += $subtotal;
                                    @endphp
                                    <tr>
                                        <td style="width: 200px">{{$item->name}}</td>
                                        <td style="width: 20px">
                                            <a href="{{url('transaction/minus/'.$item->transaction_id.'/'.$item->id)}}" class="btn btn-default btn-sm">
                                                <i class="fas fa-minus"></i>
                                            </a>
                                        </td>
                                        <td align="center" style="width: 60px">{{$item->qty}}</td>
                                        <td style="width: 20px">
                                            <a href="{{url('transaction/plus/'.$item->transaction_id.'/'.$item->id)}}" class="btn btn-default btn-sm">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </td>
                                        <td align="right">{{number_format($item->price, 2, ',', '.')}}</td>
                                        <td align="right">{{$item->discount_pct}}%</td>
                                        <td align="right">{{number_format($subtotal, 2, ',', '.')}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5">TOTAL</td>
                                    <td align="right">{{number_format($discount, 2, ',', '.')}}</td>
                                    <td align="right">
                                        {{number_format($total, 2, ',', '.')}}
                                        <input type="number" value="{{number_format($total, 2, '.', '')}}" id="tx_total_val" hidden>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td align="center" colspan="8">No Item</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">

        function applyPayment() {

            var total = $('#tx_total_val').val();
            var payment = $('#input_payment').val();
            var change = payment - total;
            $('#tx_change').text(formatNumber(change));
        }

        function applyTotal() {
            var total = $('#tx_total_val').val();
            if (total != '' && total != null) {
                $('#tx_total').text(formatNumber(total));
                console.log(total);
            }
        }

        function applyDiscount() {
            var price = $('#product_price').val();
            var qty = $('#qty').val();
            var subtotal = price * qty;
            var disc = $('#discount').val();
            var discVal = subtotal * disc / 100;
            var last_subtotal = subtotal - discVal;
            $('#discount_val').val(discVal);
            $('#subtotal').val(last_subtotal);
        }

        function applyQty() {
            var price = $('#product_price').val();
            var qty = $('#qty').val();
            $('#subtotal').val(price * qty);
        }

        function applyPrice() {
            var i = $('#product_select').find(':selected').data('price');
            $('#product_price').val((i*100)/100);
        }

        function onTypingSearch() {
            var val = $('#product_search').val();
            console.log(val);
            if (val.length > 0) {
                $('#btn-search').text("SEARCH");
            } else {
                $('#btn-search').text("REFRESH");
            }
        }

        $(function () {
                //Initialize Select2 Elements
                $('.select2').select2()

                //Initialize Select2 Elements
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })

                @if(isset($product) && count($product) > 1) 
                    $('#product_select').attr('aria-expanded', true);
                    $('.select2').show();
                @elseif(isset($product) && count($product) == 1) 
                    $('#qty').focus();
                @else
                    $('#product_search').focus();
                @endif
        })
    </script>
@endsection
@section('title', 'Form Transaction')
@section('header_tag')
<title>POS | Form Transaction</title>
<link rel="stylesheet" href="{{ url('/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"/>
<link rel="stylesheet" href="{{ url('assets/plugins/select2/css/select2.min.css') }}"/>
<link rel="stylesheet" href="{{ url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}"/>
@endsection
@section('header_script')
    <script src="{{ url('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ url('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ url('assets/plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ url('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ url('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
@endsection
