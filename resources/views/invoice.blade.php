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
            <div class="mb-3">
                <form method="GET" action="#">
                    <div class="input-group input-group-sm" style="width: 100%;">
                        <input type="text" name="table_search" class="form-control float-right"
                            placeholder="Search Bill No." id="invoice_search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row" id="invoice_result">
                @if ($billings->isEmpty())
                    <p>No Invoices Found</p>
                @else
                    @foreach ($billings as $billing)
                        @php
                            $reading_date = Carbon\Carbon::parse($billing->reading_date);
                        @endphp
                        <div class="col-md-3">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Invoice</h3>
                                </div>
                                <div class="card-body">

                                    <p><span class="font-weight-bold">Billing No :
                                        </span>{{ sprintf('%07d', $billing->id) }}</p>
                                    <p><span class="font-weight-bold">Meter Code :
                                        </span>{{ $billing->consumer->meter_code }}</p>
                                    <p><span class="font-weight-bold">Consumer Name :
                                        </span>{{ $billing->consumer->first_name }}
                                        {{ $billing->consumer->last_name }}</p>
                                    <p><span class="font-weight-bold">Status : </span>{{ $billing->status }}</p>
                                    <p><span class="font-weight-bold">Total Consumption :
                                        </span>{{ $billing->total_consumption }}</p>
                                    <p><span class="font-weight-bold">Total : </span>{{ $billing->total }}</p>
                                    <p><span class="font-weight-bold">Total Amount After Due :
                                        </span>{{ $billing->after_due }}</p>

                                </div>
                                <div class="card-footer">
                                    <a href="/billing/{{ $billing->id }}" class="btn btn-info text-right">
                                        Pay
                                    </a>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            {{-- <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reports</h3>
                    <div class="card-tools">

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
                                    <td>No Invoices Found</td>
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
            </div> --}}
        </section>
        @if (method_exists($billings, 'links'))
            <div class="d-flex justify-content-center">
                {{ $billings->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection
