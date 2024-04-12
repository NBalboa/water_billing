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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reports</h3>
                    <div class="card-tools">
                        <form method="GET" action="#">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right"
                                    placeholder="Search Bill No./Transaction No.">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Transaction No.</th>
                                <th>Billing No.</th>
                                <th>Paid At</th>
                                <th>Money</th>
                                <th>Change</th>
                                <th>Cashier Name</th>
                                <th>Consumer Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($transactions->isEmpty())
                                <tr>
                                    <td>No Reports Found</td>
                                </tr>
                            @else
                                @foreach ($transactions as $transaction)
                                    @php
                                        $paid_at = Carbon\Carbon::parse($transaction->billing->paid_at);
                                    @endphp
                                    <tr>
                                        <td>{{ sprintf('%07d', $transaction->id) }}</td>
                                        <td>{{ sprintf('%07d', $transaction->billing->id) }}</td>
                                        <td>{{ $paid_at->format('F j, Y g:i A') }}</td>
                                        <td>{{ $transaction->billing->money }}</td>
                                        <td>{{ $transaction->billing->change }}</td>
                                        <td>{{ $transaction->cashier->first_name }}
                                            {{ $transaction->cashier->last_name }}</td>
                                        <td>{{ $transaction->billing->consumer->first_name }}
                                            {{ $transaction->billing->consumer->last_name }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

        </section>
        @if (method_exists($transactions, 'links'))
            <div class="d-flex justify-content-center">
                {{ $transactions->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection
