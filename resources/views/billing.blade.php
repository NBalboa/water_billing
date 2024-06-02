@extends('admin')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <p>Reports</p>
                <div>
                    <form method="GET" action="/all/billings" class="mb-2">
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <select name="month" value="{{ old('month') }}" class="custom-select">
                                    <option value="">Select Month</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}">
                                            {{ $month = date('F', mktime(0, 0, 0, $i, 1, date('Y'))) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <select name="year" class="custom-select">
                                    <option value="">Select Year</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year->year }}">{{ $year->year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3 ">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input " type="radio" name="status" id="all" checked />
                                    <label for="all" class="form-check-label">All</label>
                                </div>
                                <div class="form-check form-check-inline">

                                    <input class="form-check-input" type="radio" name="status" value="pending"
                                        id="pending" />
                                    <label for="pending " class="form-check-label">Pending</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" value="paid"
                                        id="paid" />
                                    <label for="paid" class="form-check-label">Paid</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-auto ">
                                <div class="row">
                                    <div class="col">
                                        <label for="from" class="col-form-label">From:</label>
                                    </div>
                                    <div class="col">
                                        <input type="date" name="from" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col">
                                        <label for="to" class="col-form-label">To:</label>
                                    </div>
                                    <div class="col">
                                        <input type="date" name="to" class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-auto">
                                <input type="text" name="search" placeholder="Search ..." id="billing_search"
                                    class="form-control align-bottom" />
                            </div>
                            <div class="col-auto">
                                <input type="submit" value="Search" class="btn btn-info" style="width:100px;">
                            </div>
                        </div>

                    </form>

                </div>
            </div>
            <section class="content">
                @if (count($disconnections) > 0)
                    <a href="/billing/disconnections/print" class="btn btn-danger mb-2">Print Disconnection</a>
                @endif

                @php
                    $month = request()->query('month');
                    $year = request()->query('year');
                    $status = request()->query('status');
                    $search = request()->query('search');
                    $from = request()->query('from');
                    $to = request()->query('to');
                @endphp
                @if (!$billings->isEmpty())
                    <a href="/billings/print/receipts/{{ $month == null ? 'blank' : $month }}/{{ $year == null ? 'blank' : $year }}/{{ $status == null ? 'blank' : $status }}/{{ $search == null ? 'blank' : $search }}/{{ $from == null || $to == null ? 'blank/blank' : "{$from}/${to}" }}"
                        class="btn btn-dark mb-2">Print</a>
                @endif
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0" style="height: 300px;">
                        <table class="table table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>Meter No.</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Date Paid</th>
                                    <th>Last Month Paid</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody id="billing_result">
                                @if ($billings->isEmpty())
                                    <tr>
                                        <td>No Reports Found</td>
                                    </tr>
                                @else
                                    @foreach ($billings as $billing)
                                        @php
                                            $reading_date = Carbon\Carbon::parse($billing->reading_date);
                                            $current_date = Carbon\Carbon::now()->setTimezone('Asia/Manila');
                                            if ($billing->status == 'PAID') {
                                                $current_date = Carbon\Carbon::parse($billing->paid_at);
                                            }
                                            $result = $reading_date->diffInWeeks($current_date);
                                            $payment = $billing->price;
                                            if ($result >= 1) {
                                                if ($result > 8) {
                                                    $result = 8;
                                                }
                                                $payment = $billing->price + intval($result) * 50;
                                            }

                                            $latest_date_paid = App\Models\Billing::where(
                                                'consumer_id',
                                                $billing->consumer->id,
                                            )
                                                ->whereNotNull('paid_at')
                                                ->where('status', 'PAID')
                                                ->latest()
                                                ->first();

                                            $paid_at =
                                                $billing->paid_at !== null
                                                    ? Carbon\Carbon::parse($billing->paid_at)
                                                    : '';
                                        @endphp
                                        <tr class=
                                        "text clickable-tr {{ intval($result) >= 8 && $billing->status === 'PENDING' ? 'text-danger' : 'text-dark' }}"
                                            data-href="{{ $billing->status == 'PENDING' ? "/billing/print/$billing->id" : "/transaction/print/$billing->id" }}"
                                            style="cursor: pointer;">
                                            <td>{{ $billing->consumer->meter_code }}</td>
                                            <td>{{ $billing->consumer->first_name }}
                                                {{ $billing->consumer->last_name }}</td>
                                            <td>{{ $billing->consumer->street }},
                                                {{ $billing->consumer->barangay }}</td>
                                            @if ($billing->status === 'PAID')
                                                <td>{{ $paid_at->format('F j, Y') }}
                                                </td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td>{{ !$latest_date_paid ? '' : Carbon\Carbon::parse($latest_date_paid->paid_at)->format('F') }}
                                            </td>
                                            <td>{{ intval($result) === 0 ? $billing->total : number_format($payment, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>

                @if (method_exists($billings, 'links'))
                    <div class="d-flex justify-content-center">
                        {{ $billings->links('pagination::bootstrap-4') }}
                    </div>
                @endif

            </section>
        </section>
    </div>
@endsection
