@extends('root')
@section('header_tag')
<title>POS | Login</title>
@endsection
@section('body')
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b>Point of</b> SALE
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <p class="text-red">{{@session('error')}}</p>

      <form action="{{ isset($action)?$action:url('login') }}" method="post">
        <div class="input-group mb-3">
          @csrf
          <input type="text" name="username" class="form-control" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4 offset-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mb-1">
        <a href="{{ url('forgot_password') }}">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="{{ url('register')}}" class="text-center">Register a new membership</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
@endsection
