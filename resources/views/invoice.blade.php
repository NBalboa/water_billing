@extends('admin')



@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Invoices</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reports</h3>
                    <div class="card-tools">
                        <form method="GET" action="#">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Bill No.</th>
                                <th>Meter Code</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Total Consumption</th>
                                <th>Total Amount Due</th>
                                <th>Total Amount After Due</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($billings->isEmpty())
                                <tr>
                                    <td>No Reports Found</td>
                                </tr>
                            @else
                                @foreach ($billings as $billing)
                                    @php
                                        $reading_date = Carbon\Carbon::parse($billing->reading_date);
                                    @endphp
                                    <tr>
                                        <td>{{ sprintf('%07d', $billing->id) }}</td>
                                        <td>{{ $billing->consumer->meter_code }}</td>
                                        <td>{{ $billing->consumer->first_name }}
                                            {{ $billing->consumer->last_name }}</td>
                                        <td>{{ $billing->status }}</td>
                                        <td>{{ $billing->total_consumption }}</td>
                                        <td>{{ $billing->total }}</td>
                                        <td>{{ $billing->after_due }}</td>
                                        <td>
                                            <a href="/billing/{{ $billing->id }}" class="btn btn-info text-right">
                                                Pay
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </section>
    </div>
@endsection
