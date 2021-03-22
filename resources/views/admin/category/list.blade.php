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
                <div class="col-sm-7">
                    <div class="card card-default">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4>Data Category</h4>
                                </div>

                            </div>
                        </div>
                        <div class="card-body" style="overflow: auto;">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <th style="width: 40px">No</th>
                                <th style="width: 400px">Name</th>
                                <th></th>
                                </thead>
                                <tbody>
                                @foreach($category as $i => $item)
                                    <tr>
                                        <td>{{ ($i+1) }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td align="right">
                                            <div class="input-group-prepend">
                                                <button type="button" class="btn btn-default dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                       href="{{ url('category/edit/'.$item->id) }}">Edit</a>
                                                    <a class="dropdown-item"
                                                       href="{{ url('category/delete/'.$item->id) }}">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="card card-outline card-default">
                        <div class="card-header">
                            <h3 class="card-title">@if(@$action == 'edit') {{'Edit Category'}} @else {{'Add Category'}} @endif</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{url('category/save/'.@$id)}}">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Category Name</label>
                                    <input type="text" name="name" value="{{@$name}}" class="form-control"
                                           placeholder="Category name ..." required/>
                                </div>
                                <div class="form-group">
                                    <label for="parent">Parent category</label>
                                    <select name="parent_id" class="form-control">
                                        <option value=""></option>
                                        @foreach($category as $item)
                                            <option value="{{$item->id}}" {{($item->id == @$parent_id)?'selected':''}} >{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" type="submit">SAVE</button>
                                </div>
                            </form>
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
                    })
                }
            )
        </script>
    </section>
@endsection
@section('header_tag')
    <link rel="stylesheet"
          href="{{ url('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/select2/css/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ url('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
