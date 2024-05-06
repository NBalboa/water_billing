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
    <script src="/js/consumer_search.js"></script>
    <script src="/js/transaction_search.js"></script>
    <script src="/js/billing_search.js"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item ">
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
                                        <a href="/profile/{{ auth()->user()->id }}"
                                            class="d-block">{{ auth()->user()->first_name }}
                                            {{ auth()->user()->last_name }}</< /a>
                                    </div>
                                </div>
                            @endif
                        </li>
                        @php
                            $current = explode('/', url()->current());
                            $path = implode('/', array_slice($current, 3));
                        @endphp

                        @auth

                            @if (auth()->user()->status == 0 || auth()->user()->status == 2)
                                <li class="nav-item">
                                    <a href="/home" class="nav-link {{ $path == 'home' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-columns"></i>
                                        @if (auth()->user()->status == 0)
                                            <p>
                                                Dashboard
                                            </p>
                                        @else
                                            <p>
                                                Sales
                                            </p>
                                        @endif
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->status == 0)
                                <li class="nav-item">
                                    <a href="/all/billings" class="nav-link {{ $path == 'all/billings' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-money-bill-wave"></i>
                                        <p>
                                            Billing Reports
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/all/transactions"
                                        class="nav-link {{ $path == 'all/transactions' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                        <p>
                                            Transaction Reports
                                        </p>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->status == 2)
                                <li class="nav-item">
                                    <a href="/billing/invoice"
                                        class="nav-link {{ $path == 'billing/invoice' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-receipt"></i>
                                        <p>
                                            Invoices
                                        </p>
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a href="/consumer" class="nav-link {{ $path == 'consumer' ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Consumer
                                    </p>
                                </a>
                            </li>
                            @if (auth()->user()->status == 0)
                                <li class="nav-item menu-is-opening menu-open">
                                    <a href="#"
                                        class="nav-link {{ str_contains($path, 'admin/register') ? 'active' : '' }}">
                                        <i class="nav-icon far fa-registered"></i>
                                        <p>
                                            Register
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview" style="display: block;">
                                        {{-- <li class="nav-item">
                                            <a href="/admin/register/admin" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Admin</p>
                                            </a>
                                        </li> --}}
                                        <li class="nav-item">
                                            <a href="/admin/register/collector"
                                                class="nav-link {{ $path == 'admin/register/collector' ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Collector</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/admin/register/cashier"
                                                class="nav-link {{ $path == 'admin/register/cashier' ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Cashier</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="/admin/users" class="nav-link {{ $path == 'admin/users' ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-user-tie"></i>
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
