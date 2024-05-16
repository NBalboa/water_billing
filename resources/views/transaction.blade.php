@extends('admin')


@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Reports</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="mb-3">
                <form method="GET" action="#">
                    <div class="d-flex  align-items-center">

                        <div class="form-group mr-3">
                            <label for="month">Month</label>
                            <select name="month" value="{{ old('month') }}">
                                <option value="">Select Month</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">
                                        {{ $month = date('F', mktime(0, 0, 0, $i, 1, date('Y'))) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <label for="year">Year</label>
                            <select name="year">
                                <option value="">Select Year</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->year }}">{{ $year->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="input-group input-group-sm" style="width: 100%;">
                        <input type="text" name="table_search" class="form-control float-right" id="transaction_search"
                            placeholder="Search Bill No./Transaction No.">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @if (!$transactions->isEmpty())
                <a href="/transactions/print/{{ request()->year == null ? 'blank' : request()->year }}/{{ request()->month == null ? 'blank' : request()->month }}"
                    class="btn btn-dark mb-2">Print</a>
            @endif



            <div class="card">
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th>Meter No.</th>
                                <th>Billing No.</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Date Paid</th>
                                <th>Month Paid</th>
                                <th>Cashier Name</th>
                            </tr>
                        </thead>
                        <tbody id="transaction_result">
                            @if ($transactions->isEmpty())
                                <div>
                                    <p>No Reports Found</p>
                                </div>
                            @else
                                @foreach ($transactions as $transaction)
                                    @php
                                        $paid_at = Carbon\Carbon::parse($transaction->billing->paid_at);
                                    @endphp
                                    <tr class="text clickable-tr "
                                        data-href="/transaction/print/{{ $transaction->billing->id }}"
                                        style="cursor: pointer;">
                                        <td>{{ $transaction->billing->consumer->meter_code }}</td>
                                        <td>{{ sprintf('%07d', $transaction->billing->id) }}</td>
                                        <td>{{ $transaction->billing->consumer->first_name }}
                                            {{ $transaction->billing->consumer->last_name }}</td>
                                        <td>{{ $transaction->billing->consumer->street }},
                                            {{ $transaction->billing->consumer->barangay }}</td>
                                        <td>{{ $paid_at->format('F j, Y') }}
                                        </td>
                                        <td>{{ $paid_at->format('F') }}
                                        </td>
                                        <td>{{ $transaction->cashier->first_name }}
                                            {{ $transaction->cashier->last_name }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
        @if (method_exists($transactions, 'links'))
            <div class="d-flex justify-content-center">
                {{ $transactions->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection
