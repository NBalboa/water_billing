<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Water Billing System</title>
    {{-- <link rel="stylesheet" href="/css/app.css"> --}}
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/address.js"></script>
    <script src="/js/billing.js"></script>
    <script src="/js/pay.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>

            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <form class="nav-link" method="POST" action="/logout">
                        @csrf
                        <input type="submit" value="Logout" class="nav_link btn btn-link" />
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-1">
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                   with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            @if (auth()->check())
                                <div class="user-panel mt-3 pb-3 mb-3 d-flex">

                                    <div class="info">
                                        <a href="#" class="d-block">{{ auth()->user()->first_name }}
                                            {{ auth()->user()->last_name }}</< /a>
                                    </div>
                                </div>
                            @endif
                        </li>
                        @auth
                            <li class="nav-item">
                                <a href="/home" class="nav-link">
                                    <i class="nav-icon fas fa-columns"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/all/billings" class="nav-link">
                                    <i class="nav-icon fas fa-columns"></i>
                                    <p>
                                        Billings
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/consumer" class="nav-link">
                                    <i class="nav-icon fas fa-columns"></i>
                                    <p>
                                        Consumer
                                    </p>
                                </a>
                            </li>
                            @if (auth()->user()->status == 0)
                                <li class="nav-item menu-is-opening menu-open">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-tachometer-alt"></i>
                                        <p>
                                            Register
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="display: block;">
                                        <li class="nav-item">
                                            <a href="/admin/register/admin" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Admin</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/admin/register/collector" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Collector</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="/admin/users" class="nav-link">
                                        <i class="nav-icon fas fa-columns"></i>
                                        <p>
                                            Users
                                        </p>
                                    </a>
                                </li>
                            @endif

                        @endauth
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->

        </aside>
        @yield('content')
    </div>
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/dist/js/adminlte.min.js"></script>
</body>

</html>
