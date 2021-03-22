@extends('admin')
@section('title') Company List @endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-outline card-default {{isset($id)?'':'collapsed-card'}}">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h3 class="card-title">@if(isset($id)) {{'Edit Company'}} @else {{'Add Company'}} @endif</h3>
                                    @if(!isset($id))
                                        <button type="button" class="btn btn-sm btn-default"
                                                data-card-widget="collapse"><i
                                                class="fa fa-plus fa-fw"></i></button>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <div class="card-tools text-right">
                                        @if(!isset($id))
                                            <button type="button" class="btn btn-sm btn-default"
                                                    data-card-widget="collapse"><i
                                                    class="fa fa-plus fa-fw"></i></button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-red">{{@session("error")}}</p>
                            <form method="post" action="{{ url('myadmin/company/save/'.@$id) }}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="name">Company name</label>
                                            <input type="text" name="name" class="form-control" value="{{@$edit->name}}"
                                                   placeholder="Company name ..." required/>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="phone" name="phone" class="form-control"
                                                   value="{{@$edit->phone}}" placeholder="Phone number ..." required/>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                   value="{{@$edit->email}}" placeholder="Company email ..." required/>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Address</label>
                                            <input type="text" name="address" class="form-control"
                                                   value="{{@$edit->address}}" placeholder="Company address ..."/>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="logo">Logo</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="logo"
                                                       id="exampleInputFile">
                                                <label class="custom-file-label" for="exampleInputFile">Choose
                                                    file</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit">SAVE</button>
                                            @if(isset($id))
                                                <a class="btn btn-default" href="{{url('myadmin/company')}}">Cancel</a>
                                            @endif
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
                            <h4 class="card-title">Data Company</h4>
                        </div>
                        <div class="card-body" style="overflow: auto;">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <th style="width: 20px">No</th>
                                <th style="width: 100px">Logo</th>
                                <th style="width: 200px">Name</th>
                                <th style="width: 200px">Email</th>
                                <th style="width: 200px">Phone</th>
                                <th style="width: 400px">Address</th>
                                <th style="width: 200px">Created at</th>
                                <td style="width: 60px"></td>
                                </thead>
                                <tbody>
                                @foreach(@$company as $i => $item)
                                    <tr>
                                        <td>{{ ($i+1) }}</td>
                                        <td>
                                            <img src="{{url('img/company/'.$item->id)}}"
                                                 style="width: 100px; height: 100px">
                                        </td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->phone }}</td>
                                        <td>{{ $item->address }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td align="right">
                                            <div class="input-group-prepend">
                                                <button type="button" class="btn btn-default dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    Action
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                       href="{{ url('myadmin/company/detail/'.$item->id) }}">Detail</a>
                                                    <a class="dropdown-item"
                                                       href="{{ url('myadmin/company/edit/'.$item->id) }}">Edit</a>
                                                    <a class="dropdown-item"
                                                       href="{{ url('myadmin/company/delete/'.$item->id) }}">Delete</a>
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
    <link rel="stylesheet" href="{{ url('/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
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
