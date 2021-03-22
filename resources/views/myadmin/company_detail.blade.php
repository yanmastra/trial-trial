@extends('admin')
@section('title', 'Company Detail')
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2 text-center">
                                    <img src="{{url('img/company/'.$item->id)}}"
                                         style="width: 120px; height: 120px"></td>
                                </div>
                                <div class="col-9">
                                    <h3>{{ $item->name }}</h3>
                                    <span style="display: block">Email :{{ $item->email }}</span>
                                    <span style="display: block">Phone:{{ $item->phone }}</span>
                                    <span style="display: block">Address:{{ $item->address }}</span>
                                    <span style="display: block">Created at:{{ $item->created_at }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            @if(isset($user->id))
                                <h3>Edit User</h3>
                            @else
                                <h3>Create User</h3>
                            @endif
                        </div>
                        <div class="card-body">
                            <form action="{{url('myadmin/company/save_user/'.@$company_id)}}" method="post">
                                @csrf
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
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3>Company Users</h3>
                        </div>
                        <div class="card-body">
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
                                @foreach(@$users as $i => $item)
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
                                                   href="{{ url('company/detail/'.$item->company_id.'/user/'.$item->id) }}">Edit</a>
                                                <a  class="dropdown-item"
                                                    href="{{ url('company/user_delete/'.$item->id.'/'.$item->company_id) }}">Delete</a>
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
    </section>
@endsection
