@extends('admin')
@section('content')
    <!-- <div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Product</h1>
      </div>
    </div>
  </div>
</div> -->
    <section class="content">
        <div class="container-fluid">
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-outline card-default {{isset($edit->id)?'':'collapsed-card'}}">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <span class="card-title">@if(isset($edit->id)) {{'Edit Product '}} @else {{'Add Product '}} @endif</span>
                                    @if(!isset($edit->id))
                                    <button type="button" class="btn btn-sm btn-default" data-card-widget="collapse"><i
                                            class="fa fa-plus fa-fw"></i></button>
                                    @endif
                                </div>
                                <div class="col-sm-6 text-right">
                                    @if(!isset($edit->id))
                                        <button type="button" class="btn btn-sm btn-default" data-card-widget="collapse"><i
                                                class="fa fa-plus fa-fw"></i></button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{url('product/save/'.@$edit->id)}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="name">Product Name</label>
                                            <input type="text" name="name" class="form-control"
                                                   placeholder="Product name ..." required value="{{@$edit->name}}" autofocus/>
                                        </div>
                                        <div class="form-group">
                                            <label for="code">Product code</label>
                                            <input type="text" name="code" class="form-control"
                                                   placeholder="Product code ..." value="{{@$edit->code}}"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="category_id">Category</label>
                                            <select class="form-control select2bs4" style="width: 100%;"
                                                    name="category_id">
                                                @if(isset($category))
                                                    @foreach(@$category as $item)
                                                        <option
                                                            value="{{$item->id}}" @if(@$edit->category_id == $item->id){{'selected'}}@endif>{{$item->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">

                                        <div class="form-group">
                                            <label for="code">Cost Price</label>
                                            <input id="cost-price" type="number" name="cost_price" class="form-control"
                                                   placeholder="Cost price ..." value="{{round(@$edit->cost_price, 2)}}"/>
                                        </div>


                                        <div class="form-group">
                                        <label for="code">Margin</label>
                                        <div class="input-group">
                                            @if(isset($edit->id) && $edit->cost_price > 0)
                                            <input id="input-margin" type="number" name="margin" class="form-control" placeholder="Sell price margin ..." 
                                                value="{{round((@$edit->price - @$edit->cost_price) / @$edit->cost_price * 100, 2)}}" 
                                                onchange="onMarginChanged()"/>
                                            @else
                                            <input id="input-margin" type="number" name="margin" class="form-control" placeholder="Sell price margin ..." value="2" onchange="onMarginChanged()"/>
                                            @endif
                                            <span class="input-group-append"><button disabled class="btn btn-default"> % </button></span>
                                            <div  class="input-group-append"><button id="apply-margin" class="btn btn-info" type="button" onclick="onApplyMargin()">APPLY MARGIN</button></div>
                                        </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="code">Sell Price</label>
                                            <input id="sell-price" type="number" name="sell_price" class="form-control"
                                                   placeholder="Sell price ..." value="{{round(@$edit->price, 2)}}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        
                                        @if(!isset($edit->id))
                                        <div class="form-group">
                                            <label for="code">Stock</label>
                                            <input type="number" name="stock" class="form-control"
                                                   placeholder="Current stock ..." value="{{round(@$edit->stock, 2)}}" />
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            @if(isset($edit->id))
                                                <a href="{{url('product')}}" class="btn btn-default">Cancel</a>
                                            @endif
                                            <button class="btn btn-primary" type="submit">SAVE</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-default">
                        <div class="card-header">
                            <div class="row"><div class="col-lg-6">
                                <h4 id="card-title">Data Product</h4>
                            </div><div class="col-lg-6">
                                <form action="" method="GET">
                                <div class="input-group">
                                    <input id="product_search" type="text" name="_search" value="{{ $search }}" class="form-control" />
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">SEARCH</button>
                                    </div>
                                </div>
                                </form>
                            </div></div>
                        </div>
                        <div class="card-body" style="overflow: auto;">
                            <table class="table table-striped table-bordered" >
                                <thead>
                                <th style="width: 20px">No</th>
                                <th style="width: 140px">Code</th>
                                <th style="width: 300px">Name</th>
                                <th>Category</th>
                                <th align="right">Stock</th>
                                <th align="right">Cost Price</th>
                                <th align="right">Sell Price</th>
                                <th align="right">Margin</th>
                                <td style="width: 300px"></td>
                                </thead>
                                <tbody>
                                @foreach(@$product as $i => $item)
                                    <tr>
                                        <td>{{ ($i+1) }}</td>
                                        <td> <div><strong>{{ $item->code }}</strong> <span class="upc-barcode"> {{ $item->code }} </span></div> </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ @$item->category()->first()->name }}</td>
                                        <td>{{ @number_format($item->stock, 0, ',', '.') }}</td>
                                        <td align="right">{{ number_format($item->cost_price, 2, ',', '.') }}</td>
                                        <td align="right">{{ number_format($item->price, 2, ',', '.') }}</td>

                                        @if ($item->cost_price == 0)
                                            <td align="right">100%</td>
                                        @else
                                            <td align="right">{{ number_format(($item->price - $item->cost_price) / $item->cost_price * 100 , 2, ',', '.') }}%</td>
                                        @endif

                                        <td align="right">
                                            <a class="btn btn-outline-success btn-sm" href="{{ url('product/stock/'.$item->id) }}">Input Stock</a>
                                            <a class="btn btn-warning btn-sm" href="{{ url('product/check_stock/'.$item->id) }}">Check Stock</a>
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                    data-toggle="dropdown">
                                                Action
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('product/edit/'.$item->id) }}">Edit</a>
                                                <a  class="dropdown-item"
                                                   href="{{ url('product/delete/'.$item->id) }}">Delete</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="card-footer"> 
                            <div class="row"><div class="col-lg-2">
                            <span>Showing {{ $start }} to {{ $end }} of {{ $total_product }} Products </span>
                            </div><div class="col-lg-10">
                                <div class="input-group">
                                    <a href="{{ url('product?search='.$search.'&page='.$prev_page) }}" class="input-group-prepend">
                                        <button type="button" class="btn btn-info" style="width: 140px"> Previous </button>
                                    </a>
                                    @if ($page > 2) 
                                        <a href="{{ url('product?search='.$search.'&page=1') }}" class="input-group-prepend">
                                            <button type="button" class="btn btn-default" style="width: 140px"> Back to Page 1 </button>
                                        </a>
                                    @endif
                                    <div class="input-group-append">
                                        <span class="form-control" style="width: 300px; text-align: center;">Page {{ $page }} </span>
                                    </div>
                                    <a href="{{ url('product?search='.$search.'&page='.$next_page) }}" class="input-group-append">
                                            <button type="button" class="btn btn-info" style="width: 140px"> Next </button>
                                    </a>
                                </div>
                            </div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            function onMarginChanged() {
                var marginInput = $('#input-margin').val();
                console.log('input margin=' + marginInput);
                if (marginInput > 0) {
                    $('#apply-margin').removeClass('disabled');
                } else {
                    $('#apply-margin').addClass('disabled');
                }
            }

            function onApplyMargin() {
                var costPrice = $('#cost-price').val();
                var marginInput = $('#input-margin').val();
                var sellPrice = costPrice * (1 + (marginInput / 100));
                $('#sell-price').val(sellPrice);
            }

            $(function () {
                    //Initialize Select2 Elements
                    $('.select2').select2()

                    //Initialize Select2 Elements
                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    });

                    $(function () {
                        $('#table_product').DataTable({
                            "paging": false,
                            "lengthChange": false,
                            "searching": true,
                            "ordering": false,
                            "info": false,
                            "autoWidth": true,
                        });

                        @if(isset($search) && $search != '') $('#product_search').focus(); @endif
                    })
                }
            )
        </script>
    </section>
@endsection
@section('header_tag')
    <link rel="stylesheet" href="{{ url('/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/select2/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+EAN13+Text&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <style>
        .upc-barcode,
        .ean-barcode {
            font-family: 'Libre Barcode 128', sans-serif;
            font-size: 42px;
            transform: scale(.5, 1);
        }
    </style>
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
    <script src="{{ url('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
@endsection
