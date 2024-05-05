@extends('admin')

@section('content')
    <!-- Main Sidebar Container -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        @if (auth()->user()->status == 0)
                            <h1>Dashboard</h1>
                        @else
                            <h1>Sales</h1>
                        @endif
                    </div>
                </div>
                @if (auth()->user()->status == 0)
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-users"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total Consumer</span>
                                    <span class="info-box-number">{{ $total_consumer }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-file-invoice"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Pending Bill</span>
                                    <span class="info-box-number">{{ $total_pending }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning">
                                    <i class="fas fa-receipt"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total Paid</span>
                                    <span class="info-box-number">{{ $total_paid }}</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </span>

                            <div class="info-box-content">
                                <span class="info-box-text">Daily Income</span>
                                <span class="info-box-number">{{ $daily_sales }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-success">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Monthly Income</span>
                                <span class="info-box-number">{{ $monthly_sales }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <!-- /.col -->
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            {{-- <h1>Hello</h1> --}}
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
@endsection
<!-- Site wrapper -->
