@extends('root')
@section('header_tag')
    <title>POS |
@yield('title')</title>
@endsection
@section('body')
    <body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ url()->previous() }}" class="nav-link">BACK</a>
                </li>
            </ul>
            <form class="form-inline ml-3 float-right">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search"
                           aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="../../index3.html" class="brand-link">
                <img src="{{ url('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                     class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light"> {{ @(auth()->user()->get_company()->name) }} </span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ url('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                             alt="User Image">
                    </div>
                    <div class="info">
                        <a href="{{url('/')}}" class="d-block">{{auth()->user()->name}}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="{{ (auth()->user()->type == 'SYSTEM')?url("myadmin/company"):url('dashboard') }}"
                               class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        @if(auth()->user()->type == 'SYSTEM')
                            <li class="nav-item">
                                <a href="{{ url('myadmin/company') }}" class="nav-link">
                                    <i class="nav-icon fas fa-building"></i>
                                    <p>Companies</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('myadmin/user') }}" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Root Users</p>
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ url('transaction') }}" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-cart"></i>
                                    <p>Transaction</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('close_cash') }}" class="nav-link">
                                    <i class="nav-icon fas fa-calendar"></i>
                                    <p>Close Cash</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('product') }}" class="nav-link">
                                    <i class="nav-icon fas fa-cubes"></i>
                                    <p>Product</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('log_stock') }}" class="nav-link">
                                    <i class="nav-icon fas fa-clock"></i>
                                    <p>Stock Logs</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('category') }}" class="nav-link">
                                    <i class="nav-icon fas fa-list-alt"></i>
                                    <p>Category</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ url('user') }}" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('loss_profit') }}" class="nav-link">
                                    <i class="nav-icon fas fa-list-alt"></i>
                                    <p>Profit and loss</p>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ url('logout') }}" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>
    @yield('footer_script')
    <script type="text/javascript">
        function confirmDelete(url) {
            var a = confirm("Apakah anda yakin untuk ini ?");
            if (a) window.location = url;
        }
    </script>
    </body>
@endsection
