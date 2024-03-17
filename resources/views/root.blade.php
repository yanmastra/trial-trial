<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{url('assets/plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="{{ url('assets/dist/css/ionicons.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/dist/css/adminlte.min.css') }}">
  <link href="{{ url('assets/dist/css/font-googleapis.css') }}" rel="stylesheet">
@yield('header_tag')

  <script src="{{ url('assets/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@yield('header_script')
  <script src="{{ url('assets/dist/js/adminlte.min.js') }}"></script>
  <script src="{{ url('assets/dist/js/demo.js') }}"></script>
@yield('script')
</head>
@yield('body')
</html>
