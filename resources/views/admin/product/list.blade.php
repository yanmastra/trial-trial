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
                                    </div>
                                    <div class="col-sm-4">
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
                                        <div class="form-group">
                                            <label for="code">Cost Price</label>
                                            <input type="number" name="cost_price" class="form-control"
                                                   placeholder="Cost price ..." value="{{round(@$edit->cost_price, 2)}}"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="code">Sell Price</label>
                                            <input type="number" name="sell_price" class="form-control"
                                                   placeholder="Sell price ..." value="{{round(@$edit->price, 2)}}" />
                                        </div>
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
                                    <input type="text" name="search" value="{{ $search }}" class="form-control" />
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
                                <td style="width: 60px"></td>
                                </thead>
                                <tbody>
                                @foreach(@$product as $i => $item)
                                    <tr>
                                        <td>{{ ($i+1) }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ @$item->category()->first()->name }}</td>
                                        <td>{{ @number_format($item->stock, 0, ',', '.') }}</td>
                                        <td align="right">{{ number_format($item->cost_price, 2, ',', '.') }}</td>
                                        <td align="right">{{ number_format($item->price, 2, ',', '.') }}</td>
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
                                    <div class="input-group-append">
                                        <span class="form-control" style="width: 100px; text-align: center;"> {{ $page }} </span>
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
