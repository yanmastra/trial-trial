@extends('admin')
@section('title', 'User List')
@section('content')
    <br>
    <style>
        .btn-focused:focus{
            border: solid 2px #128294;
            box-shadow: #c1d1df;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-outline card-default {{isset($user->id)?'':'collapsed-card'}}">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h2>@if(isset($user->id)) {{'Edit User '}} @else {{'Add User '}} @endif</h2>
                                    @if(!isset($user->id))
                                        <button type="button" class="btn btn-sm btn-default" data-card-widget="collapse"><i
                                                class="fa fa-plus fa-fw"></i></button>
                                    @endif
                                </div>
                                <div class="col-sm-6 text-right">
                                    @if(!isset($user->id))
                                        <button type="button" class="btn btn-sm btn-default" data-card-widget="collapse"><i
                                                class="fa fa-plus fa-fw"></i></button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{url('user/save/'.@$user->id)}}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <p>Input user data here!</p>
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" name="username" class="form-control"
                                                   placeholder="Username..." value="{{@$user->username}}" required autofocus/>
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" class="form-control"
                                                   placeholder="Name ..." required value="{{@$user->name}}" autofocus/>
                                        </div>
                                        <div class="form-group">
                                            <label for="code">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                   placeholder="username@example.com" value="{{@$user->email}}"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        @if(isset($user->id))
                                            <p>Input password if you want to change the password</p>
                                        @else
                                            <p>Input password for authenticate login</p>
                                        @endif
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" class="form-control"
                                                   placeholder="Password..." />
                                        </div>
                                        <div class="form-group">
                                            <label for="confrim_password">Confirm Password</label>
                                            <input type="password" name="confirm_password" class="form-control"
                                                   placeholder="Password..." />
                                        </div>
                                        <div class="form-group">
                                            <br>
                                            <button type="submit" class="btn btn-default btn-lg btn-focused">
                                                <i class="fas fa-save"></i>
                                                Save
                                            </button>
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
                            <h4 id="card-title">Data User</h4>
                        </div>
                        <div class="card-body" style="overflow: auto;">
                            <table class="table table-striped table-bordered" id="table_product" >
                                <thead>
                                <th style="width: 20px">No.</th>
                                <th style="width: 140px">Name</th>
                                <th style="width: 140px">Username</th>
                                <th>Email</th>
                                <th>Created at</th>
                                <th></th>
                                </thead>
                                <tbody>
                                @foreach(@$list as $i => $item)
                                    <tr>
                                        <td>{{ ($i+1) }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->username }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td align="right">
                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle"
                                                    data-toggle="dropdown">
                                                Action
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item"
                                                   href="{{ url('user/edit/'.$item->id) }}">Edit</a>
                                                <a  class="dropdown-item"
                                                    href="{{ url('user/delete/'.$item->id) }}">Delete</a>
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
