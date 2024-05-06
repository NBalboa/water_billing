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
            <div class="row" id="transaction_result">
                @if ($transactions->isEmpty())
                    <p>No Reports Found</p>
                @else
                    @foreach ($transactions as $transaction)
                        @php
                            $paid_at = Carbon\Carbon::parse($transaction->billing->paid_at);
                        @endphp
                        <div class="col-md-3">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Transaction</h3>
                                </div>
                                <div class="card-body">
                                    <p><span class="font-weight-bold">Transaction
                                            No: </span>{{ sprintf('%07d', $transaction->id) }}</p>
                                    <p><span class="font-weight-bold">Billing No:
                                        </span>{{ sprintf('%07d', $transaction->billing->id) }}</p>
                                    <p><span class="font-weight-bold">Cashier:
                                        </span>{{ $transaction->cashier->first_name }}
                                        {{ $transaction->cashier->last_name }}</p>
                                    <p><span class="font-weight-bold">Consumer:
                                        </span>{{ $transaction->billing->consumer->first_name }}
                                        {{ $transaction->billing->consumer->last_name }}</p>
                                    <p><span class="font-weight-bold">Paid: </span>{{ $paid_at->format('F j, Y g:i A') }}
                                    </p>
                                    <p><span class="font-weight-bold">Amount: </span>{{ $transaction->billing->money }}</p>
                                    <p><span class="font-weight-bold">Change: </span>{{ $transaction->billing->change }}</p>

                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>


        </section>
        @if (method_exists($transactions, 'links'))
            <div class="d-flex justify-content-center">
                {{ $transactions->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
@endsection
